<?php
session_start();

include 'header.php'; // 包含 header

// 获取购物车数据
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_amount = 0;

// Check if cart is valid (not empty and all items have required fields)
$is_cart_valid = true;
if (empty($cart)) {
    $is_cart_valid = false;
} else {
    foreach ($cart as $item) {
        if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity']) || 
            empty($item['name']) || $item['price'] <= 0 || $item['quantity'] <= 0) {
            $is_cart_valid = false;
            break;
        }
    }
}

// Calculate total amount if cart is valid
if ($is_cart_valid) {
    foreach ($cart as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkout.css">
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="checkout-container">
        <div class="order-summary">
            <h2>Your Recipient</h2>
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Image</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cart)) : ?>
                        <?php foreach ($cart as $item) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 80px; height: auto;"></td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5">Your cart is empty</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <p><strong>Total :</strong> $<?php echo number_format($total_amount, 2); ?></p>
        </div>
    
        <form action="../backend/process_order.php" method="post" id="checkoutForm">
            <div class="form-group">
                <label for="recipient_name">Name:</label>
                <input type="text" id="recipient_name" name="recipient_name" required>
            </div>
            <div class="form-group">
                <label for="street">Street:</label>
                <input type="text" id="street" name="street" required>
            </div>
            <div class="form-group">
                <label for="city">City/Suburb:</label>
                <input type="text" id="city" name="city" required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <select id="state" name="state" required>
                    <option value="">Select State</option>
                    <option value="NSW">NSW</option>
                    <option value="VIC">VIC</option>
                    <option value="QLD">QLD</option>
                    <option value="WA">WA</option>
                    <option value="SA">SA</option>
                    <option value="TAS">TAS</option>
                    <option value="ACT">ACT</option>
                    <option value="NT">NT</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="form-group">
                <label for="mobile_number">Mobile Number:</label>
                <input type="tel" id="mobile_number" name="mobile_number" 
                    pattern="[0-9]{10}" 
                    title="Please enter a 10-digit phone number (e.g., 0412345678)"
                    required>
                <div class="error-message"></div>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" 
                    title="Please enter a valid email address (e.g., user@example.com)"
                    required>
                <div class="error-message"></div>
            </div>
            
            <div class="action-buttons">
                <button type="submit" id="submitBtn" disabled>Submit</button>
                <a href="cart_detail.php" class="back-btn">Back to Cart</a>
            </div>
        </form>
    </div>

    <script src="js/validate.js"></script>
</body>
</html>
