<?php
session_start();
include 'config.php'; // Database connection

// Example cart items (In real implementation, retrieve these from session or database)
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error'] = "Your cart is empty.";
    header("Location: cart.php");
    exit;
}

$cart_items = $_SESSION['cart'];

// Calculate total amount, items list, and quantities
$total_amount = 0;
$items_list = [];
$quantities = [];

foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
    $items_list[] = $item['name'];
    $quantities[] = $item['quantity'];
}

// Convert items list and quantities to comma-separated strings
$items_list_str = implode(', ', $items_list);
$quantities_str = implode(', ', $quantities);

// If form is submitted, process the order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_name = $_POST['first_name'] . ' ' . $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $country = $_POST['country'];
    $payment_method = $_POST['payment_method'];
    $status = 'Pending';

    // Insert order into database
    $insertOrder = "INSERT INTO orders (customer_name, email, phone, address, city, state, zip_code, country, payment_method, status, total_amount, items_list, quantities)
                    VALUES (:customer_name, :email, :phone, :address, :city, :state, :zip_code, :country, :payment_method, :status, :total_amount, :items_list, :quantities)";
    $stmt = $pdo->prepare($insertOrder);

    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':zip_code', $zip_code);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':payment_method', $payment_method);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':total_amount', $total_amount);
    $stmt->bindParam(':items_list', $items_list_str);
    $stmt->bindParam(':quantities', $quantities_str);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Order placed successfully!";
        unset($_SESSION['cart']); // Clear cart after successful order
        header("Location: confirmation.php");
        exit;
    } else {
        $_SESSION['error'] = "Failed to place order.";
        header("Location: checkout.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Chinthaka Enterprises</title>
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

    <!-- Checkout Section -->
    <section class="checkout">
        <div class="container">
            <h2>Checkout</h2>

            <?php if (isset($_SESSION['success'])): ?>
                <p class="success-message"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php elseif (isset($_SESSION['error'])): ?>
                <p class="error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <form action="checkout.php" method="POST">
                <div class="checkout-container">
                    <!-- Customer Information and Billing Details -->
                    <div class="billing-section">
                        <h3>Customer Information</h3>
                        <input type="email" name="email" placeholder="Email Address" required>

                        <h3>Billing Details</h3>
                        <div class="billing-details">
                            <input type="text" name="first_name" placeholder="First Name *" required>
                            <input type="text" name="last_name" placeholder="Last Name *" required>
                            <input type="text" name="address" placeholder="House number and street name" required>
                            <input type="text" name="city" placeholder="City *" required>
                            <input type="text" name="state" placeholder="State *" required>
                            <input type="text" name="zip_code" placeholder="ZIP Code *" required>
                            <input type="text" name="country" placeholder="Country *" required>
                            <input type="tel" name="phone" placeholder="Phone *" required>
                        </div>

                        <h3>Additional Information</h3>
                        <textarea name="notes" placeholder="Notes about your order, e.g., special notes for delivery." rows="3"></textarea>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <h3>Your Order</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total = 0;
                                foreach ($cart_items as $item): 
                                    $subtotal = $item['price'] * $item['quantity'];
                                    $total += $subtotal;
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['name']) . ' × ' . $item['quantity']; ?></td>
                                        <td>LKR <?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td><strong>LKR <?php echo number_format($total, 2); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- Payment Section -->
                        <h3>Payment</h3>
                        <div class="payment-options">
                            <label>
                                <input type="radio" name="payment_method" value="Cash on Delivery" checked>
                                Cash on delivery
                                <p>Pay with cash upon delivery.</p>
                            </label>
                            <label>
                                <input type="radio" name="payment_method" value="Bank Transfer">
                                Bank transfer
                                <p>Transfer directly to our bank account.</p>
                            </label>
                            <label>
                                <input type="radio" name="payment_method" value="Card Payment">
                                Card Payment (VISA/Master)
                                <p>Pay securely with your credit or debit card.</p>
                            </label>
                        </div>
                        <!-- Place Order Button -->
                        <button type="submit" class="btn place-order-btn">Place Order LKR <?php echo number_format($total, 2); ?></button>
                    </div>
                </div>
            </form>
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
$pdo = null; // Close the database connection
?>
