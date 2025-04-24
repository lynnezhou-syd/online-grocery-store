<?php
@session_start();
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Grocery Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/search.css">
    <link rel="stylesheet" href="css/subnav.css">
    <link rel="stylesheet" href="css/product_details.css">
    <link rel="icon" href="images/store_icon.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="js/sidebar.js"></script>
    <script src="js/search.js" ></script>
    <script src="js/cart.js"></script>
</head>
<body>

<header class="main-header">
    
    <div class="header-left">
        <div class="logo-container">
            <a href="index.php">
                <img src="images/logo.png" alt="Store Logo" class="store-logo">
            </a>
        </div>
    </div>




    <?php
    $excluded_pages = ['cart_detail.php', 'checkout.php', 'confirmation.php'];
    $current_page = basename($_SERVER['PHP_SELF']);
    if (!in_array($current_page, $excluded_pages)) {
    ?>
        <div class="search-container">
            <form action="search.php" method="get" onsubmit="return redirectToSearchResults()">
                <div class="search-wrapper">
                    <input type="text" id="searchBox" name="query" placeholder="Search products">
                    <button type="button" class="clear-btn" onclick="clearSearch()">×</button>
                </div>
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Search
                </button>
            </form>
            <div id="search-results" class="search-results"></div>
        </div>
    <?php } ?>


    <div class="header-right">
        <a href="cart_detail.php" class="cart-icon-wrapper">
            <div class="cart-icon-container">
                <i class="fas fa-shopping-cart cart-icon"></i>
                <div class="cart-badge pulse" id="cart-total">0</div>
            </div>
            <span class="cart-text">Cart</span>
        </a>
    </div>
</header>

<div class="sub-header"></div>

<?php
// 定义不需要显示分类的页面
$excluded_pages = ['cart_detail.php', 'delivery.php', 'confirmation.php'];
$current_page = basename($_SERVER['PHP_SELF']);
?>

<?php if (!in_array($current_page, $excluded_pages)): ?>
    <button class="sidebar-toggle browse-btn-large" onclick="toggleSidebar()" 
            style="position: fixed; top: 120px; left: 0; z-index: 999; padding: 10px 15px;">
        ☰ <span class="toggle-label">Browse products</span>
    </button>

    <!-- 侧边栏 -->
    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-user-info">
                <span>Our Products</span>
            </div>
            <button class="close-btn" onclick="toggleSidebar()">✕</button>
        </div>

        <!-- 侧边栏内容 -->
        <div class="sidebar-content">
            <div class="sidebar-section">
                <ul>
                    <!-- Frozen 冷冻食品 -->
                    <li class="category-item">
                        <a href="products.php?category=frozen" class="category-link">
                            <i class="fas fa-snowflake"></i> Frozen
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="sub-categories">
                            <li><a href="products.php?category=frozen&subcategory=seafood"><i class="fas fa-fish"></i> Frozen Seafood</a></li>
                            <li><a href="products.php?category=frozen&subcategory=meals"><i class="fas fa-pizza-slice"></i> Frozen Meals</a></li>
                        </ul>
                    </li>

                    <!-- Fresh 新鲜食品 -->
                    <li class="category-item">
                        <a href="products.php?category=fresh" class="category-link">
                            <i class="fas fa-leaf"></i> Fresh
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="sub-categories">
                            <li><a href="products.php?category=fresh&subcategory=fruits"><i class="fas fa-apple-alt"></i> Fruits</a></li>
                            <li><a href="products.php?category=fresh&subcategory=vegetables"><i class="fas fa-carrot"></i> Vegetables</a></li>
                        </ul>
                    </li>

                    <!-- Beverages 饮料 -->
                    <li class="category-item">
                        <a href="products.php?category=beverage" class="category-link">
                            <i class="fas fa-glass-cheers"></i> Beverages
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="sub-categories">
                            <li><a href="products.php?category=beverage&subcategory=juices"><i class="fas fa-glass-whiskey"></i> Juices</a></li>
                            <li><a href="products.php?category=beverage&subcategory=hot-drinks"><i class="fas fa-mug-hot"></i> Hot Drinks</a></li>
                        </ul>
                    </li>

                    <!-- Pet-food 宠物食品 -->
                    <li class="category-item">
                        <a href="products.php?category=petfood" class="category-link">
                            <i class="fas fa-paw"></i> Pet Food
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="sub-categories">
                            <li><a href="products.php?category=petfood&subcategory=dog-food"><i class="fas fa-dog"></i> Dog Food</a></li>
                            <li><a href="products.php?category=petfood&subcategory=cat-food"><i class="fas fa-cat"></i> Cat Food</a></li>
                        </ul>
                    </li>

                    <!-- Household 生活用品 -->
                    <li class="category-item">
                        <a href="products.php?category=household" class="category-link">
                            <i class="fas fa-home"></i> Household
                            <i class="fas fa-chevron-down dropdown-icon"></i>
                        </a>
                        <ul class="sub-categories">
                            <li><a href="products.php?category=household&subcategory=cleaning-supplies"><i class="fas fa-broom"></i> Cleaning Supplies</a></li>
                            <li><a href="products.php?category=household&subcategory=personal-care"><i class="fas fa-soap"></i> Personal Care</a></li>
                            <li><a href="products.php?category=household&subcategory=pet-care"><i class="fas fa-paw"></i> Pet Care</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>


