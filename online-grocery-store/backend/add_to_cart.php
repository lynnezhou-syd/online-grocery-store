<?php
session_start();
error_log(print_r($_SESSION['cart'], true));
include_once("db.php");


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            addToCart();
            break;
        case 'remove':
            removeFromCart();
            break;
        case 'clear':
            clearCart();
            break;
        case 'update':
            updateCartQuantity();
            break;
        case 'count':
            getCartCount();
            break;
        case 'total':
            getTotal();
            break;
        case 'get':
            getCart();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
    exit;
}

// -----------------------
// get cart total
// -----------------------
function getTotal() {
    $total = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    }
    echo json_encode(['success' => true, 'total' => $total]);
}

// -----------------------
// get cart
// -----------------------
function getCart() {
    // 无论购物车是否为空，返回 success 为 true，cart 为数组（空数组时前端可据此显示“购物车为空”）
    if (isset($_SESSION['cart'])) {
        echo json_encode(['success' => true, 'cart' => array_values($_SESSION['cart'])]);
    } else {
        echo json_encode(['success' => true, 'cart' => []]);
    }
}

// -----------------------
// add products to cart 
// -----------------------
function addToCart() {
    global $conn;

    if (!isset($_POST['product_id'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID is required']);
        return;
    }

    $productId = (int)$_POST['product_id'];

    // Query database to get product information
    $stmt = $conn->prepare("SELECT id, name, price, image_url FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Product not found']);
        return;
    }

    $product = $result->fetch_assoc();

    // Initialize Shopping Cart Array
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    
    if (!isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId] = [
            'id'       => $productId,              
            'name'     => $product['name'],
            'quantity' => 0,
            'price'    => $product['price'],
            'image'    => $product['image_url']
        ];
    }

    
    $_SESSION['cart'][$productId]['quantity'] += 1;

    
    $count = 0;
    foreach ($_SESSION['cart'] as $item) {
        $count += $item['quantity'];
    }

    echo json_encode([
        'success' => true,
        'message' => 'Added ' . $product['name'] . ' to cart',
        'cart'    => $_SESSION['cart'],
        'count'   => $count
    ]);
}

// --------------------------
// delete product from cart
// --------------------------
function removeFromCart() {
    if (!isset($_POST['product_id'])) {
        echo json_encode(['success' => false]); // 只返回成功状态
        return;
    }

    $productId = (int)$_POST['product_id'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
        echo json_encode(['success' => true]); // 只返回成功状态
    } else {
        echo json_encode(['success' => false]); // 只返回成功状态
    }
}

// -----------------------
// clear all
// -----------------------
function clearCart() {
    if (!empty($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'message' => 'Cart cleared']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Cart is already empty']);
    }
}

// -----------------------
// update cart quantity
// -----------------------
function updateCartQuantity() {
    if (!isset($_POST['product_id'], $_POST['quantity'])) {
        echo json_encode(['success' => false, 'message' => 'Product ID and quantity are required']);
        return;
    }

    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    
    if ($quantity === 0) {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            echo json_encode(['success' => true, 'message' => 'Product removed from cart']);
        } else {
            echo json_encode(['success' => false]);
        }
        return;
    }

    
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]['quantity'] = $quantity;
        echo json_encode(['success' => true, 'message' => 'Quantity updated']);
    } else {
        echo json_encode(['success' => false]);
    }
}

// -----------------------
// get total count
// -----------------------
function getCartCount() {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += $item['quantity'];
        }
    }
    echo json_encode(['count' => $count]);
}
?>
