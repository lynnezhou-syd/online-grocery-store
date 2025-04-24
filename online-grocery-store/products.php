<?php
session_start();
include_once('header.php');
include_once('backend/db.php');

// 获取分类参数
$category = isset($_GET['category']) ? strtolower($_GET['category']) : ''; // 将分类参数转换为小写
$subcategory = isset($_GET['subcategory']) ? strtolower($_GET['subcategory']) : '';

// 定义允许的分类及其对应的 ID
$allowedCategories = [
    'frozen' => 1, 
    'fresh' => 2,  
    'beverage' => 3, 
    'petfood' => 4,
    'household' => 5,
];

$allowedSubcategories = [
    'seafood' => 1, 
    'ready-meals' => 2, 
    'fruits' => 3, 
    'vegetables' => 4, 
    'juices' => 5, 
    'hot-drinks' => 6, 
    'dog-food' => 7, 
    'cat-food' => 8, 
    'cleaning-supplies' => 9, 
    'personal-care' => 10, 
    'pet-care' => 11,
];

// 检查分类是否有效
if (!array_key_exists($category, $allowedCategories)) {
    die("Invalid category.");
}

// 获取分类 ID
$category_id = $allowedCategories[$category];

// 获取子分类 ID（如果存在）
$subcategory_id = null;
if ($subcategory && array_key_exists($subcategory, $allowedSubcategories)) {
    $subcategory_id = $allowedSubcategories[$subcategory];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php 
        echo ucfirst($category); 
        echo $subcategory ? ' - ' . ucfirst($subcategory) : ''; 
        ?> Products
    </title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/product_details.css">
</head>
<body>
    <div class="content">
        <h2>
            <?php 
            echo ucfirst($category); 
            if ($subcategory) {
                echo ' <span class="subcategory-name"> ' . ucfirst($subcategory) . '</span>';
            }
            ?> 
        </h2>
        
        <?php
        include 'display_products.php';
        displayProducts($category_id, '', $subcategory_id);
        ?>
    </div>

    <script src="js/cart.js"></script>
    <script src="js/search.js"></script>
    <?php include_once('footer.php'); ?>
</body>
</html>