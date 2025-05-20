<?php
session_start();
include 'config.php'; // Database connection

// Check if the order was just placed
if (!isset($_SESSION['last_order_id'])) {
    header("Location: index.php"); // Redirect to home if no order ID
    exit;
}

// Fetch the order details using the last order ID
$order_id = $_SESSION['last_order_id'];
$query = "SELECT id, customer_name, created_at, status, payment_method, total_amount, items_list, quantities 
          FROM orders 
          WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$order_id]);
$order_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the order was found
if (!$order_info) {
    echo "Order not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation - Chinthaka Enterprises</title>
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
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="cart.php" class="cart">Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Order Confirmation Section -->
    <section class="order-confirmation">
        <div class="container">
            <h2>Thank you for your order!</h2>
            <p class="confirmation-message">Your order has been successfully placed.</p>

            <div class="order-details">
                <h3>Order Summary</h3>
                <p><strong>Order Number:</strong> #<?php echo htmlspecialchars($order_info['id']); ?></p>
                <p><strong>Order Date:</strong> <?php echo date("F j, Y", strtotime($order_info['created_at'])); ?></p>
                <p><strong>Order Status:</strong> <?php echo htmlspecialchars($order_info['status']); ?></p>
                <p><strong>Estimated Delivery:</strong> 5-7 business days</p>
                <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order_info['payment_method']); ?></p>

                <h4>Items Ordered:</h4>
                <p><strong>Items List:</strong> <?php echo htmlspecialchars($order_info['items_list']); ?></p>
                <p><strong>Quantities:</strong> <?php echo htmlspecialchars($order_info['quantities']); ?></p>

                <p><strong>Total Amount:</strong> LKR <?php echo number_format($order_info['total_amount'], 2); ?></p>
            </div>

            <a href="shop.php" class="btn continue-shopping-btn">Continue Shopping</a>
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

<?php
unset($_SESSION['last_order_id']); // Clear the order ID after display
$pdo = null; // Close the database connection
?>
