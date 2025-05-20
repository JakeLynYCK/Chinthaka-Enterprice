<?php
session_start();
include 'config.php'; // Database connection using PDO
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop - Chinthaka Enterprises</title>
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

    <!-- Categories Sidebar -->
    <section class="categories">
        <div class="container">
            <h2>Categories</h2>
            <ul class="category-list">
                <?php
                // Fetch categories using PDO
                $categoryQuery = "SELECT id, name FROM categories ORDER BY name";
                $stmt = $pdo->prepare($categoryQuery);
                $stmt->execute();
                
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($categories) {
                    foreach ($categories as $category) {
                        echo '<li><a href="#category-' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</a></li>';
                    }
                } else {
                    echo "<li>No categories available.</li>";
                }
                ?>
            </ul>
        </div>
    </section>

    <!-- All Products Section -->
    <section class="all-products">
        <div class="container">
            <h2>All Products</h2>
            <?php
            // Fetch categories and products using PDO
            $categoryProductQuery = "
                SELECT categories.id AS category_id, categories.name AS category_name, 
                       products.id AS product_id, products.name AS product_name, 
                       products.price, products.image 
                FROM categories 
                LEFT JOIN products ON products.category_id = categories.id 
                ORDER BY categories.name, products.name";
            $stmt = $pdo->prepare($categoryProductQuery);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $currentCategory = null;

            if ($products) {
                foreach ($products as $row) {
                    if ($currentCategory !== $row['category_id']) {
                        if ($currentCategory !== null) {
                            echo '</div>'; // Close .product-grid
                            echo '</div>'; // Close .product-category
                        }

                        $currentCategory = $row['category_id'];
                        echo '<div id="category-' . $currentCategory . '" class="product-category">';
                        echo '<h3>' . htmlspecialchars($row['category_name']) . '</h3>';
                        echo '<div class="product-grid">';
                    }

                    if ($row['product_id'] !== null) {
                        echo '<div class="product">';
                        echo '<img src="./Chinthaka-Enterprice-Owner/' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['product_name']) . '">';
                        echo '<h3>' . htmlspecialchars($row['product_name']) . '</h3>';
                        echo '<p>LKR ' . number_format($row['price'], 2) . '</p>';
                        echo '<a href="product.php?id=' . $row['product_id'] . '" class="btn">View Details</a>';
                        echo '</div>';
                    }
                }

                echo '</div>'; // Close .product-grid
                echo '</div>'; // Close .product-category
            } else {
                echo "<p>No products available.</p>";
            }
            ?>
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
