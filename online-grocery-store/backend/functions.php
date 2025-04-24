<?php
function getProductsByQuery($conn, $query) {
    $sql = "SELECT id, name, price, image_url FROM products WHERE name LIKE CONCAT('%', ?, '%')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// 获取分类名称
function getCategoryName($conn, $category_id) {
    $stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return htmlspecialchars($row['name']);
    }
    return 'Uncategorized';
}

?>