<?php
include_once('header.php');
include_once('backend/db.php');
include_once('backend/functions.php'); // 引入公共函数
include_once('backend/render_products.php'); // 引入渲染函数

$query = $_GET['query'] ?? '';

$products = getProductsByQuery($conn, $query); // 获取搜索商品
?>

<?php
function displayProducts($category_id = null, $query = '', $subcategory_id = null, $exclude_subcategory_id = null) {
    include_once 'backend/db.php';
    global $conn;

    // 查询商品
    if ($category_id && $subcategory_id) {
        // 如果同时传入 category_id 和 subcategory_id，查询匹配的商品
        if ($exclude_subcategory_id) {
            // 如果传入 exclude_subcategory_id，排除指定的子分类
            $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND subcategory_id = ? AND subcategory_id NOT IN (?)");
            $stmt->bind_param("iii", $category_id, $subcategory_id, $exclude_subcategory_id);
        } else {
            $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND subcategory_id = ?");
            $stmt->bind_param("ii", $category_id, $subcategory_id);
        }
    } elseif ($category_id) {
        // 如果只传入 category_id，查询该分类下的所有商品
        $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ?");
        $stmt->bind_param("i", $category_id);
    } elseif (!empty($query)) {
        // 如果传入查询关键词，搜索匹配的商品
        $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
        $searchQuery = "%$query%";
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
    } else {
        // 如果没有传入任何参数，查询所有商品
        $stmt = $conn->prepare("SELECT * FROM products");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // 展示商品
    if ($result->num_rows > 0) {
        echo '<div class="grid-container">';
        while ($product = $result->fetch_assoc()) {
            echo '
            <div class="grid-item transparent-card">
                <img src="' . htmlspecialchars($product['image_url']) . '" alt="' . htmlspecialchars($product['name']) . '">
                
                <h3>' . htmlspecialchars($product['name']) . '</h3>
                
                <p class="price-unit">$' . number_format($product['price'], 2) . ' / ' . htmlspecialchars($product['unit']) . '</p>
                <p>' . htmlspecialchars($product['description']) . '</p>

                <!-- 库存状态 -->
                <p class="stock-status ' . ($product['stock'] <= 0 ? 'out-of-stock' : 'in-stock') . '" 
                    id="stock-status-' . $product['id'] . '" 
                    data-stock="' . $product['stock'] . '">
                    ' . ($product['stock'] > 0 ? 'In Stock: ' . $product['stock'] . '' : 'Out of Stock') . '
                </p>


                <!-- 默认显示的 Add to Cart 按钮 -->
                <button 
                    id="add-to-cart-' . $product['id'] . '" 
                    class="add-to-cart-btn" 
                    onclick="addToCart(' . $product['id'] . ')"
                    ' . ($product['stock'] <= 0 ? 'disabled' : '') . '
                >
                    Add to Cart
                </button>
            </div>';
        }
        echo '</div>';
    } else {
        echo '<p>No products found.</p>';
    }
}
?>