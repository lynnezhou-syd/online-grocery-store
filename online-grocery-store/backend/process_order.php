<?php
session_start();
require_once('db.php');


$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Database connection failed"]));
}


$required_fields = [
    'recipient_name' => 'Name',
    'street' => 'Street',
    'city' => 'City/Suburb',
    'state' => 'State',
    'mobile_number' => 'Mobile Number',
    'email' => 'Email Address'
];

$order_data = [];
foreach ($required_fields as $field => $name) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "$name is required"]);
        exit;
    }
    $order_data[$field] = trim($_POST[$field]);
}


$conn->begin_transaction();

try {
    
    if (empty($_SESSION['cart'])) {
        throw new Exception("Your cart is empty");
    }

    $total_amount = 0;
    $order_items = [];
    $stock_errors = [];

    foreach ($_SESSION['cart'] as $product_id => $item) {
        if (!isset($item['id'], $item['quantity'], $item['price'], $item['name'])) {
            throw new Exception("Invalid cart item format");
        }

        
        $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$product) {
            throw new Exception("Product not found");
        }
        
        if ($product['stock'] < $item['quantity']) {
            $stock_errors[] = "{$item['name']}: Ordered {$item['quantity']}, only {$product['stock']} left";
        }

        $total_amount += $item['price'] * $item['quantity'];
        $order_items[] = $item;
    }

    
    if (!empty($stock_errors)) {
        throw new Exception("Stock issues:\n" . implode("\n", $stock_errors));
    }

    
    $order_stmt = $conn->prepare("
        INSERT INTO orders (
            recipient_name, street, city, state, 
            mobile_number, email, total_amount, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    $order_stmt->bind_param(
        "ssssssd",
        $order_data['recipient_name'],
        $order_data['street'],
        $order_data['city'],
        $order_data['state'],
        $order_data['mobile_number'],
        $order_data['email'],
        $total_amount
    );
    $order_stmt->execute();
    $order_id = $conn->insert_id;
    $order_stmt->close();

    
    foreach ($order_items as $item) {
        
        $detail_stmt = $conn->prepare("
            INSERT INTO order_items (
                order_id, product_id, quantity, price
            ) VALUES (?, ?, ?, ?)
        ");
        $detail_stmt->bind_param(
            "iiid",
            $order_id,
            $item['id'],
            $item['quantity'],
            $item['price']
        );
        $detail_stmt->execute();
        $detail_stmt->close();

        
        $update_stmt = $conn->prepare("
            UPDATE products 
            SET stock = stock - ? 
            WHERE id = ? AND stock >= ?
        ");
        $update_stmt->bind_param(
            "iii",
            $item['quantity'],
            $item['id'],
            $item['quantity']
        );
        $update_stmt->execute();
        
        if ($update_stmt->affected_rows === 0) {
            throw new Exception("Failed to update stock for product: " . $item['name']);
        }
        $update_stmt->close();
    }

    
    $conn->commit();
    
    
    $_SESSION['order_confirmation'] = [
        'order_id' => $order_id,
        'recipient_name' => $order_data['recipient_name'],
        'total_amount' => $total_amount,
        'items' => $order_items
    ];
    
    
    unset($_SESSION['cart']);
    
    echo json_encode(['success' => true]);
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
} finally {
    $conn->close();
}
?>