<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/cart.css">
    <link rel="stylesheet" href="css/sidebar.css">
    
</head>
<body>
    <div class="cart-container">
        <h1>Your Shopping Cart</h1>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Image</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody id="cartTable">
                <?php
                $total = 0;
                if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
                    foreach ($_SESSION['cart'] as $id => $item) {
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                        echo "
                        <tr id='row-{$id}'>
                            <td>{$item['name']}</td>
                            <td><img src='{$item['image']}' style='width: 50px;'></td>
                            <td>$" . number_format($item['price'], 2) . "</td>
                            <td>
                                <input type='number' min='1' value='{$item['quantity']}' 
                                       onchange='updateQuantity({$id}, this.value)'>
                            </td>
                            <td id='subtotal-{$id}'>$" . number_format($subtotal, 2) . "</td>
                            <td><button onclick='removeItem({$id})'><i class='fas fa-trash'></i></button></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Your cart is empty </td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h3>Total: <span id="totalAmount">$0.00</span></h3>
        <div class="cart-actions">
            <div class="left-actions">
                <button onclick="clearCart()">Clear Cart</button>
                <a href="checkout.php">
                    <button id="checkoutButton" disabled>Place an Order</button>
                </a>
            </div>
            <div class="right-actions">
                <a href="index.php" class="back-to-home">Continue Shopping</a>
            </div>
        </div>    
    </div>
 
    <script src="cart.js"></script>
</body>
</html>