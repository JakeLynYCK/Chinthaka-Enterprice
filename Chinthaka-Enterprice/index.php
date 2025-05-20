<?php
session_start();
include 'config.php'; // Database connection

// Capture search term from the URL
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chinthaka Enterprises - Home</title>
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
                    <li><a href="order-status.php">Order Tracking</a></li>
                    <li><a href="cart.php" class="cart">Cart</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Search Bar -->
    <section class="search-bar">
        <div class="container">
            <form action="index.php" method="GET">
                <input type="text" name="search" placeholder="Search for products..." class="search-input" value="<?= htmlspecialchars($searchTerm) ?>">
                <button type="submit" class="search-btn"><i class="bi bi-search"></i> Search</button>
            </form>
        </div>
    </section>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Your One-Stop Shop for Quality Automotive Spare Parts</h2>
        <p>Explore a wide range of products and enjoy the convenience of online shopping.</p>
        <a href="shop.php" class="btn">Shop Now</a>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <div class="container">
            <h2>Featured Products</h2>
            <div class="product-grid">
                <?php
                // Query to get featured products based on search
                $query = "SELECT products.id, products.name, products.price, products.image 
                          FROM products 
                          LEFT JOIN categories ON products.category_id = categories.id 
                          WHERE (products.name LIKE :search OR categories.name LIKE :search)";
                
                $stmt = $pdo->prepare($query);
                $searchTermWithWildcards = '%' . $searchTerm . '%';
                $stmt->bindParam(':search', $searchTermWithWildcards);
                $stmt->execute();
                
                $featuredProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if ($featuredProducts) {
                    foreach ($featuredProducts as $product) { ?>
                        <div class="product">
                            <img src="Chinthaka-Enterprice-Owner/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>LKR <?= number_format($product['price'], 2) ?></p>
                            <a href="product.php?id=<?= $product['id'] ?>" class="btn">View Details</a>
                        </div>
                    <?php }
                } else {
                    echo "<p>No products found for '$searchTerm'.</p>";
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Additional Sections from Original Template -->
    <section class="additional-section">
        <div class="container">
            <h2>Why Choose Us</h2>
            <p>Our commitment to quality and customer satisfaction makes us a trusted name in the automotive industry.</p>
            <div class="services-grid">
                <div class="service">
                    <h3>Quality Parts</h3>
                    <p>We offer only the best quality automotive spare parts to ensure your vehicle's performance.</p>
                </div>
                <div class="service">
                    <h3>Wide Range</h3>
                    <p>From brakes to filters, we have everything you need for your vehicle's maintenance.</p>
                </div>
                <div class="service">
                    <h3>Reliable Service</h3>
                    <p>Our customer service team is always ready to assist you with your needs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup Section -->
    <section class="newsletter">
        <div class="container">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter for the latest offers and product news.</p>
            <form>
                <input type="email" placeholder="Enter your email">
                <button type="submit" class="btn">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Chinthaka Enterprises. All rights reserved.</p>
            <div class="social">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">LinkedIn</a>
            </div>
        </div>
    </footer>
</body>
</html>

<?php
$pdo = null; // Close the database connection
?>
