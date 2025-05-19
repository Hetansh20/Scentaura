<?php
session_start();
require 'vendor/autoload.php'; 
require 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $conn->real_escape_string($_SESSION['username']);

// Fetch user_id from full_name
$user_query = $conn->query("SELECT id, email FROM users WHERE full_name = '$username' LIMIT 1");
if ($user_query && $user_query->num_rows === 1) {
    $user = $user_query->fetch_assoc();
    $user_id = intval($user['id']);
    $user_email = $user['email'];
} else {
    session_destroy();
    header('Location: login.php');
    exit();
}

// Fetch cart items for user
$sql = "SELECT c.quantity, p.id AS product_id, p.name, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);

$cart_items = [];
$total_price_cents = 0; // Stripe expects amount in cents

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['price_cents'] = intval(round($row['price'] * 100));
        $row['total_cents'] = $row['price_cents'] * $row['quantity'];
        $total_price_cents += $row['total_cents'];
        $cart_items[] = $row;
    }
} else {
    // No items in cart
    echo "<p>Your cart is empty. <a href='products.php'>Shop now</a>.</p>";
    exit();
}

// Setup Stripe
\Stripe\Stripe::setApiKey('sk_test_51RQQCJ2MNpuo9KJdSN0pMGOoSLLaC687bhvOHa1JaO4CoeFae8Hu2YYEXEaUaZ7ZCQGkVpgh5OCf30nxHhoMub0d00x4QygBBe'); // Replace with your Stripe Secret Key

// Create Stripe Checkout Session with line items from cart
$line_items = [];
foreach ($cart_items as $item) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => $item['name'],
            ],
            'unit_amount' => $item['price_cents'],
        ],
        'quantity' => $item['quantity'],
    ];
}

$YOUR_DOMAIN = 'http://localhost/ScentAura';

$checkout_session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => $line_items,
    'mode' => 'payment',
    'customer_email' => $user_email,
    'metadata' => [
        'user_id' => $user_id,
    ],
    'success_url' => $YOUR_DOMAIN . '/success.php?session_id={CHECKOUT_SESSION_ID}',
    'cancel_url' => $YOUR_DOMAIN . '/checkout.php',
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Checkout - ScentAura</title>
<link rel="stylesheet" href="components/css/checkout.css?v=<?php echo time(); ?>">
<script src="https://js.stripe.com/v3/"></script>
</head>
<body>
  <div class="container">
    <h1>Checkout</h1>
    <table>
      <thead>
        <tr>
          <th>Product</th>
          <th>Quantity</th>
          <th style="text-align:right;">Price</th>
          <th style="text-align:right;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cart_items as $item): ?>
        <tr>
          <td data-label="Product"><?= htmlspecialchars($item['name']) ?></td>
          <td data-label="Quantity"><?= $item['quantity'] ?></td>
          <td data-label="Price" style="text-align:right;">$<?= number_format($item['price'], 2) ?></td>
          <td data-label="Subtotal" style="text-align:right;">$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="total">Total: $<?= number_format($total_price_cents / 100, 2) ?></div>
    <button id="checkout-button">Pay with Card</button>
  </div>
  <script>
    var stripe = Stripe('pk_test_51RQQCJ2MNpuo9KJduoqDvqSwl0OJk8m7QhRV2yyJ1yc09RpdVzF7DRpKsIAch3T23kX2IoHih0ukOd0FZV5GcL7M00Zb2SgJFu'); // Replace with your Stripe Publishable Key
    var checkoutButton = document.getElementById('checkout-button');
    checkoutButton.addEventListener('click', function () {
      stripe.redirectToCheckout({ sessionId: '<?= $checkout_session->id ?>' }).then(function (result) {
        if (result.error) {
          alert(result.error.message);
        }
      });
    });
  </script>
</body>
</html>
