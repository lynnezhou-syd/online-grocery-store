<?php
function renderProducts($products) {
    if (empty($products)) {
        echo "<p>No products found 😢</p>";
        return;
    }

    foreach ($products as $product) {
        echo '
        <div class="product-item">
            <img src="' . $product['image_url'] . '" alt="' . $product['name'] . '">
            <div class="product-info">
                <h2>' . $product['name'] . '</h2>
                <p>$' . $product['price'] . '</p>
                <button onclick="addToCart(' . $product['id'] . ')"> Add to Cart</button>
            </div>
        </div>';
    }
}
?>