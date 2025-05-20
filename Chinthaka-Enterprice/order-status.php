<?php
session_start();
include 'config.php'; // Database connection

// Check if the form is submitted
$order_info = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    
    // Fetch order details from the database
    $query = "SELECT id, status, created_at FROM orders WHERE id = :order_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->execute();
    $order_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order_info) {
        $error_message = "Order not found. Please check your Order ID.";
    } else {
        // Set estimated delivery date based on the order status
        $delivery_date = $order_info['status'] === 'Shipped' ? date("F j, Y", strtotime($order_info['created_at'] . ' + 7 days')) : 'TBD';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Status - Chinthaka Enterprises</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <h1 class="logo">Chinthaka Enterprises</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="cart.php" class="cart">Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Order Status Section -->
    <section class="order-status">
        <div class="container">
            <h2>Check Your Order Status</h2>
            <p>Enter your Order ID to check the status of your order.</p>

            <form action="order-status.php" method="POST">
                <input type="text" name="order_id" placeholder="Enter Order ID" required>
                <button type="submit" class="btn check-status-btn">Check Status</button>
            </form>

            <!-- Display Order Status Result -->
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php elseif ($order_info): ?>
                <div class="order-status-result">
                    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order_info['id']); ?></p>
                    <p><strong>Status:</strong> <?php echo htmlspecialchars($order_info['status']); ?></p>
                    <p><strong>Estimated Delivery:</strong> <?php echo htmlspecialchars($delivery_date); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Chinthaka Enterprises. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
