<?php
session_start();
require 'backend/db.php';

// Validate order data
if (empty($_SESSION['order_confirmation']['order_id'])) {
    $_SESSION['checkout_error'] = "Invalid order confirmation";
    header("Location: checkout.php");
    exit;
}

$order_id = $_SESSION['order_confirmation']['order_id'];

// Query order details
$order_stmt = $conn->prepare("
    SELECT o.*, 
           GROUP_CONCAT(p.name SEPARATOR ', ') AS product_names,
           SUM(oi.quantity) AS total_items
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    WHERE o.id = ?
    GROUP BY o.id
");
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order = $order_stmt->get_result()->fetch_assoc();
$order_stmt->close();

if (!$order) {
    unset($_SESSION['order_confirmation']);
    $_SESSION['checkout_error'] = "Order not found";
    header("Location: checkout.php");
    exit;
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="css/confirmation.css">
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Add animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .email-notification {
            background-color: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .email-notification p {
            margin: 8px 0;
            position: relative;
            z-index: 2;
        }

        .email-icon {
            font-size: 24px;
            margin-right: 10px;
            color: #28a745;
            vertical-align: middle;
            animation: bounce 2s infinite;
        }

        .envelope {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 60px;
            color: rgba(40, 167, 69, 0.1);
            z-index: 1;
        }

        .progress-bar {
            height: 4px;
            background: #e9ecef;
            margin-top: 15px;
            border-radius: 2px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            width: 0;
            background: #28a745;
            transition: width 3s ease-in-out;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-10px);}
            60% {transform: translateY(-5px);}
        }

        @keyframes float {
            0% {transform: translateY(0px);}
            50% {transform: translateY(-10px);}
            100% {transform: translateY(0px);}
        }

        .checkmark {
            display: inline-block;
            width: 22px;
            height: 22px;
            background: #28a745;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 22px;
            margin-right: 8px;
            animation: popIn 0.5s ease-out;
        }

        @keyframes popIn {
            0% {transform: scale(0);}
            80% {transform: scale(1.1);}
            100% {transform: scale(1);}
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2 class="animate__animated animate__fadeInDown">Order Confirmed!</h2>
        
        <div class="email-notification animate__animated animate__fadeIn">
            <div class="envelope animate__animated animate__float">
                <i class="fas fa-envelope"></i>
            </div>
            <p>
                <span class="checkmark"><i class="fas fa-check"></i></span>
                <strong>Confirmation email sent!</strong>
            </p>
            <p>
                <i class="fas fa-paper-plane email-icon"></i>
                We've sent your order details to <strong><?= htmlspecialchars($order['email']) ?></strong>
            </p>
            <p><i class="fas fa-info-circle"></i> Please check your inbox (and spam folder if you don't see it).</p>
            
            <div class="progress-bar">
                <div class="progress" id="emailProgress"></div>
            </div>
        </div>
        
        <div class="order-summary animate__animated animate__fadeInUp">
            <h3><i class="fas fa-receipt"></i> Order Summary</h3>
            <p><strong>Order ID:</strong> #<?= htmlspecialchars($order['id']) ?></p>
            <p><strong>Recipient:</strong> <?= htmlspecialchars($order['recipient_name']) ?></p>
            <p><strong>Delivery Address:</strong> 
                <?= htmlspecialchars($order['street']) ?>, 
                <?= htmlspecialchars($order['city']) ?>, 
                <?= htmlspecialchars($order['state']) ?>
            </p>
            <p><strong>Contact:</strong> 
                <?= htmlspecialchars($order['mobile_number']) ?> | 
                <?= htmlspecialchars($order['email']) ?>
            </p>
            <p><strong>Products:</strong> <?= htmlspecialchars($order['product_names']) ?></p>
            <p><strong>Total Items:</strong> <?= $order['total_items'] ?></p>
            <p><strong>Total Amount:</strong> $<?= number_format($order['total_amount'], 2) ?></p>
        </div>
        
        <a href="index.php" class="continue-shopping animate__animated animate__fadeIn">
            <i class="fas fa-shopping-bag"></i> Continue Shopping
        </a>
    </div>

    <script>
        // Animate progress bar
        document.addEventListener('DOMContentLoaded', function() {
            // Progress bar animation
            setTimeout(function() {
                document.getElementById('emailProgress').style.width = '100%';
            }, 300);
            
            // Add floating animation to envelope after delay
            setTimeout(function() {
                document.querySelector('.envelope').classList.add('animate__float');
            }, 500);
            
            // Add hover effect to shopping button
            const shopBtn = document.querySelector('.continue-shopping');
            shopBtn.addEventListener('mouseenter', function() {
                this.classList.add('animate__pulse');
            });
            shopBtn.addEventListener('mouseleave', function() {
                this.classList.remove('animate__pulse');
            });
        });
    </script>
</body>
</html>

<?php
// Clear order session data
unset($_SESSION['order_confirmation']);
include 'footer.php';
?>