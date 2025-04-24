<?php
include_once('db.php');

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode(['stock' => $product['stock']]);
} else {
    echo json_encode(['stock' => 0]);
}
?>