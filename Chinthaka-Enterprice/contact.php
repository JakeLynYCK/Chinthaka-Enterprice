<?php
session_start();
include 'config.php'; // Database connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Insert contact form data into the feedback table
    $query = "INSERT INTO feedback (customer_name, email, message) VALUES (:name, :email, :message)";
    $stmt = $pdo->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    // Execute the statement and check if successful
    if ($stmt->execute()) {
        $success_message = "Thank you! Your message has been sent.";
    } else {
        $error_message = "There was an error sending your message. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Chinthaka Enterprises</title>
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

    <!-- Contact Us Section -->
    <section class="contact-us">
        <div class="container">
            <h2>Contact Us</h2>
            <p>If you have any questions, please feel free to get in touch with us, and we'll get back to you shortly.</p>

            <div class="contact-details">
                <h3>Contact Details</h3>
                <p><i class="bi bi-envelope-fill"></i> <strong>Email:</strong> contact@info.com</p>
                <p><i class="bi bi-telephone-fill"></i> <strong>Phone:</strong> +1 234 567 890</p>
                <p><i class="bi bi-geo-alt-fill"></i> <strong>Address:</strong> 123 Fifth Avenue, New York, NY 10160</p>

                <div class="social-links">
                    <h3>Follow Us</h3>
                    <a href="#"><i class="bi bi-facebook"></i></a>
                    <a href="#"><i class="bi bi-twitter"></i></a>
                    <a href="#"><i class="bi bi-youtube"></i></a>
                </div>
            </div>

            <!-- Display success or error messages -->
            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php elseif (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form action="contact.php" method="POST" class="contact-form">
                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn">Send</button>
            </form>
        </div>
    </section>

    <!-- Useful Links Section -->
    <section class="useful-links">
        <div class="container">
            <h2>Useful Links</h2>
            <div class="links-grid">
                <div class="link-item">
                    <i class="bi bi-people-fill"></i>
                    <h3>Partnerships</h3>
                    <p>Interested in a partnership with us?</p>
                    <a href="#" class="btn">Apply Here</a>
                </div>
                <div class="link-item">
                    <i class="bi bi-question-circle-fill"></i>
                    <h3>FAQ</h3>
                    <p>Most questions can be answered here.</p>
                    <a href="#" class="btn">Go to FAQ</a>
                </div>
                <div class="link-item">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h3>Store Locations</h3>
                    <p>Find your nearest Chinthaka Enterprises store.</p>
                    <a href="#" class="btn">Find Store</a>
                </div>
            </div>
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