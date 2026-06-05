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
$stripe_secret_key = getenv('STRIPE_SECRET_KEY') ?: '';
\Stripe\Stripe::setApiKey($stripe_secret_key); // Loaded from env

// Apply promo code discount if valid
$discount_amount_cents = 0;
if (isset($_SESSION['promo_code']) && $total_price_cents >= ($_SESSION['min_purchase'] * 100)) {
    if ($_SESSION['discount_type'] == 'percentage') {
        $discount_amount_cents = round($total_price_cents * ($_SESSION['discount_value'] / 100));
    } else {
        $discount_amount_cents = round($_SESSION['discount_value'] * 100);
    }
}

// Adjust line items dynamically so Stripe accepts the discounted total
if ($discount_amount_cents > 0 && $discount_amount_cents < $total_price_cents) {
    foreach ($cart_items as &$item) {
        $proportion = $item['total_cents'] / $total_price_cents;
        $item_discount = round($discount_amount_cents * $proportion);
        // Distribute discount into the unit price
        $item['price_cents'] = max(0, $item['price_cents'] - round($item_discount / $item['quantity']));
        $item['total_cents'] = $item['price_cents'] * $item['quantity'];
    }
    unset($item);
    
    // Recalculate total to ensure it matches exactly due to rounding
    $total_price_cents = array_sum(array_column($cart_items, 'total_cents'));
}

// Create Stripe Checkout Session with line items from cart
$line_items = [];
foreach ($cart_items as $item) {
    $line_items[] = [
        'price_data' => [
            'currency' => 'usd',
            'product_data' => [
                'name' => $item['name'] . (isset($_SESSION['promo_code']) ? " (Discounted)" : ""),
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
    var stripe = Stripe('<?= htmlspecialchars(getenv('STRIPE_PUBLISHABLE_KEY') ?: '') ?>'); // Loaded from env
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
