<?php
session_start();
include 'config.php'; // Database connection using PDO

// Check if product ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: shop.php"); // Redirect to shop if no product ID
    exit;
}

// Fetch product details from the database
$product_id = intval($_GET['id']);
$query = "SELECT products.name AS product_name, products.price, products.image, products.description, categories.name AS category_name
          FROM products
          JOIN categories ON products.category_id = categories.id
          WHERE products.id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "Product not found.";
    exit;
}

// Handle Add to Cart functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    // Add product to cart session
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $cart_item = [
        'id' => $product_id,
        'name' => $product['product_name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'image' => $product['image']
    ];
    
    $_SESSION['cart'][] = $cart_item;
    header("Location: cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['product_name']); ?> - Chinthaka Enterprises</title>
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

    <!-- Product Details Section -->
    <section class="product-details">
        <div class="container">
            <div class="product-gallery">
                <div class="main-image">
                    <img src="Chinthaka-Enterprice-Owner/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
            </div>
            <div class="product-info">
                <p class="breadcrumb"><?php echo htmlspecialchars($product['category_name']); ?></p>
                <h1><?php echo htmlspecialchars($product['product_name']); ?></h1>
                <p class="price">LKR <?php echo number_format($product['price'], 2); ?> & Free Shipping</p>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                <form method="POST" action="">
                    <div class="purchase-options">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="decrease">-</button>
                            <input type="number" id="quantity" name="quantity" value="1" min="1">
                            <button type="button" class="increase">+</button>
                        </div>
                        <button type="submit" class="btn add-to-cart">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Chinthaka Enterprises. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const quantityInput = document.getElementById("quantity");
            document.querySelector(".decrease").onclick = () => {
                if (quantityInput.value > 1) quantityInput.value--;
            };
            document.querySelector(".increase").onclick = () => quantityInput.value++;
        });
    </script>
</body>
</html>

<?php
$pdo = null; // Close the database connection
?>
