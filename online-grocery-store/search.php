<?php
// search.php
include_once('header.php');
include_once('backend/db.php');
include_once('backend/functions.php'); 
include_once('display_products.php');


$query = $_GET['query'] ?? '';

$page_title = $query ? 'Search Results for "' . htmlspecialchars($query) . '"' : 'Products';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="css/styles.css"> 
    <link rel="stylesheet" href="css/product_details.css"> 
<body>
    <main class="content">
        <h1><?php echo $page_title; ?></h1>
        <div class="grid-container">
            <?php displayProducts(null, $query); ?>
        </div>
    </main>
</body>
</html>

<?php
include_once('footer.php');
?>