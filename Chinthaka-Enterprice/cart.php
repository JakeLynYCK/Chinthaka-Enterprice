<?php
session_start();
include 'config.php'; // Database connection

// Initialize the cart session if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle Clear Cart action
if (isset($_POST['clear_cart'])) {
    $_SESSION['cart'] = []; // Empty the cart
    header("Location: cart.php"); // Refresh the page
    exit;
}

// Fetch products in the cart
$cartItems = $_SESSION['cart'];
$total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Chinthaka Enterprises</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
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

    <!-- Cart Section -->
    <section class="cart">
        <div class="container cart-container">
            <h2>Your Shopping Cart</h2>

            <?php if (empty($cartItems)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <!-- Cart Table -->
                <div class="cart-table">
                    <form method="post" action="cart.php">
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                    <?php
                                    // Calculate subtotal for each item
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                    ?>
                                    <tr>
                                        <td class="product-info">
                                            <img src="assets/images/Products/<?php echo htmlspecialchars($item['image']); ?>" alt="Product Image" class="product-image">
                                            <span><?php echo htmlspecialchars($item['name']); ?></span>
                                        </td>
                                        <td>LKR <?php echo number_format($item['price'], 2); ?></td>
                                        <td>
                                            <div class="quantity-controls">
                                                <button class="decrease">-</button>
                                                <input type="number" value="<?php echo $item['quantity']; ?>" min="1">
                                                <button class="increase">+</button>
                                            </div>
                                        </td>
                                        <td>LKR <?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Cart Totals -->
                        <div class="cart-totals">
                            <h3>Cart Totals</h3>
                            <div class="totals-row">
                                <span>Subtotal</span>
                                <span>LKR <?php echo number_format($total, 2); ?></span>
                            </div>
                            <div class="totals-row">
                                <span>Total</span>
                                <span>LKR <?php echo number_format($total, 2); ?></span>
                            </div>
                            <p>Have a coupon?</p>
                            <button type="button" onclick="window.location.href='checkout.php'" class="btn checkout-btn">Checkout</button>
                        </div>
                    </form>

                    <!-- Clear Cart Button -->
                    <form method="post" action="cart.php" style="margin-top: 10px;">
                        <button type="submit" name="clear_cart" class="btn clear-cart-btn" style="background-color: #f44336; color: white;">
                            Clear Cart
                        </button>
                    </form>
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
