<?php
// index.php
include 'header.php';

?>
<div class="content">
    <h2>  Welcome to the RunBerry Grocery Store!</h2>
    <p>   Discover our wide range of fresh and quality products.</p>
    <div class="grid-container">
        <?php
        include 'backend/db.php';
        include 'display_products.php';
        displayProducts(); 
        ?>
    </div>
</div>
<?php
include 'footer.php';
?>