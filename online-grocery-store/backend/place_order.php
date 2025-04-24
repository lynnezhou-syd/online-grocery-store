<?php
session_start();
include_once('db.php');

$name = $_POST['name'];
$address = $_POST['address'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];

$cartData = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$totalAmount = 0;
foreach ($cartData as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}


$sql_order = "INSERT INTO orders (user_name, email, phone, address, order_date, total_amount)
VALUES ('$name', '$email', '$mobile', '$address', NOW(), $totalAmount)";

if ($conn->query($sql_order)) {
    $order_id = $conn->insert_id;

    
    foreach ($cartData as $item) {
        $sql_item = "INSERT INTO order_items (order_id, product_id, quantity, unit_price)
        VALUES ($order_id, {$item['id']}, {$item['quantity']}, {$item['price']})";
        
        $conn->query($sql_item);
    }

    
    mail($email, "Order Confirmation", "Your order (#$order_id) has been placed successfully.");

    header("Location: ../confirmation.php");
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>