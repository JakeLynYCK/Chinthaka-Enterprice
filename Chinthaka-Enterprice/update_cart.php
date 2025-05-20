<?php
session_start();

// Check if cart exists in session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Update quantities in the cart based on POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantities'])) {
    $quantities = $_POST['quantities'];
    
    // Iterate over cart items and update quantities
    foreach ($quantities as $index => $quantity) {
        // Ensure the quantity is a positive integer
        $quantity = max(1, (int)$quantity);
        
        // Update the session cart with the new quantity
        if (isset($_SESSION['cart'][$index])) {
            $_SESSION['cart'][$index]['quantity'] = $quantity;
        }
    }
}

// Redirect back to the cart page after updating
header("Location: cart.php");
exit;
?>
