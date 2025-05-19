<?php
session_start();
require 'vendor/autoload.php'; // For PHPMailer, FPDF, Stripe etc

include 'db.php';

// Assuming user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch user_id & email from users table by username
$username = $conn->real_escape_string($_SESSION['username']);
$user_q = $conn->query("SELECT id, email FROM users WHERE full_name='$username'");
// $id = $user_q->fetch_assoc()['id'] ?? null;
// echo $id;
if (!$user_q || $user_q->num_rows !== 1) {
    die('User not found');
}
$user = $user_q->fetch_assoc();
$user_id = (int)$user['id'];
$user_email = $user['email'];

// Fetch cart items for user
$cart_res = $conn->query("SELECT c.quantity, p.id AS product_id, p.name, p.price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$user_id");
if (!$cart_res || $cart_res->num_rows === 0) {
    die('Cart empty');
}
$cart_items = [];
$total_amount = 0.00;
while ($row = $cart_res->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['price'] * $row['quantity'];
}

// Dummy payment data after successful payment - replace with actual from your payment gateway
$payment_status = 'completed';
$order_status = 'confirmed';
$payment_method = 'Stripe';  // Example
$transaction_id = 'txn_'.bin2hex(random_bytes(8)); // Replace with actual transaction id
$shipping_address = $conn->real_escape_string($_POST['shipping_address'] ?? '');
$billing_address = $conn->real_escape_string($_POST['billing_address'] ?? '');
$order_date = date('Y-m-d H:i:s');

// Insert order
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, payment_status, order_status, payment_method, transaction_id, shipping_address, billing_address, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('idsssssss', $user_id, $total_amount, $payment_status, $order_status, $payment_method, $transaction_id, $shipping_address, $billing_address, $order_date);
if (!$stmt->execute()) {
    die("Failed to insert order: " . $stmt->error);
}
$order_id = $stmt->insert_id;
$stmt->close();

// Insert order items
$stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($cart_items as $item) {
    $pid = (int)$item['product_id'];
    $qty = (int)$item['quantity'];
    $price = (float)$item['price'];
    $stmt_item->bind_param('iiid', $order_id, $pid, $qty, $price);
    if (!$stmt_item->execute()) {
        die("Failed to insert order item: " . $stmt_item->error);
    }
}
$stmt_item->close();

// Clear cart for user
$conn->query("DELETE FROM cart WHERE user_id=$user_id");

// Generate PDF invoice (requires FPDF)
require('fpdf/fpdf.php');

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial','B',16);
        $this->Cell(0,10,'ScentAura Invoice',0,1,'C');
        $this->Ln(5);
    }
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Thank you for shopping with ScentAura!',0,0,'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,"Invoice for Order #$order_id",0,1);
$pdf->Cell(0,10,"Customer: $username",0,1);
$pdf->Cell(0,10,"Email: $user_email",0,1);
$pdf->Cell(0,10,"Order Date: $order_date",0,1);
$pdf->Ln(10);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(80,10,'Product',1);
$pdf->Cell(30,10,'Qty',1);
$pdf->Cell(40,10,'Price',1);
$pdf->Cell(40,10,'Total',1);
$pdf->Ln();

$pdf->SetFont('Arial','',12);
foreach ($cart_items as $item) {
    $total_item = $item['price'] * $item['quantity'];
    $pdf->Cell(80,10, $item['name'],1);
    $pdf->Cell(30,10, $item['quantity'],1,0,'C');
    $pdf->Cell(40,10, '$'.number_format($item['price'],2),1,0,'R');
    $pdf->Cell(40,10, '$'.number_format($total_item,2),1,0,'R');
    $pdf->Ln();
}
$pdf->SetFont('Arial','B',12);
$pdf->Cell(150,10,'Total',1);
$pdf->Cell(40,10,'$'.number_format($total_amount,2),1,0,'R');

$invoice_file = sys_get_temp_dir() . "/invoice_order_$order_id.pdf";
$pdf->Output('F', $invoice_file);

// Send confirmation emails (requires PHPMailer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    // User email
    $mail->setFrom('scentaura25@gmail.com', 'ScentAura');
    $mail->addAddress($user_email, $username);
    $mail->Subject = "Order Confirmation #$order_id";
    $mail->Body = "Dear $username,\n\nThank you for your order! Please find your invoice attached.\n\nRegards,\nScentAura Team";
    $mail->addAttachment($invoice_file);
    $mail->send();

    // Admin email
    $mail->clearAddresses();
    $mail->addAddress('scentaura25@gmail.com.com', 'ScentAura Admin');
    $mail->Subject = "New Order Received #$order_id";
    $mail->Body = "A new order has been placed by $username.\nOrder ID: $order_id\nTotal: $" . number_format($total_amount,2);
    $mail->addAttachment($invoice_file);
    $mail->send();
} catch (Exception $e) {
    error_log('Mail error: ' . $mail->ErrorInfo);
}

unlink($invoice_file);

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8" />
    <title>Order Successful - ScentAura</title>
    <link rel="stylesheet" href="components/css/success.css">
    </head>
    <body>
        <h1>Thank you for your purchase!</h1>
        <p>Your order #<?= $order_id ?> has been received and a confirmation email has been sent to <?= htmlspecialchars($user_email) ?>.</p>
        <p><a href="index.php">Continue Shopping</a></p>
    </body>
</html>
