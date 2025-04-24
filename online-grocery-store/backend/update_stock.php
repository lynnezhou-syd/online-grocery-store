<?php
header('Content-Type: application/json');
include_once('db.php');

try {
    
    $productId = intval($_POST['product_id']);
    $quantityToDeduct = intval($_POST['quantity']); 
    
    
    if ($productId <= 0 || $quantityToDeduct <= 0) {
        throw new Exception("Invalid parameters");
    }

    
    $sql = "UPDATE products 
            SET stock = stock - ? 
            WHERE id = ? AND stock >= ?"; // Preventing overselling
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $quantityToDeduct, $productId, $quantityToDeduct);
    
    if (!$stmt->execute()) {
        throw new Exception("Database error");
    }

    // check update
    if ($stmt->affected_rows === 0) {
        throw new Exception("Insufficient stock or product not found");
    }

    echo json_encode(['success' => true]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?>