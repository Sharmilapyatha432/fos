<?php
session_start();

// If cart doesn't exist yet, create it
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['food_id'], $_POST['quantity'])) {

    $food_id  = (int) $_POST['food_id'];
    $name     = isset($_POST['name'])  ? trim($_POST['name'])  : '';
    $price    = isset($_POST['price']) ? (float) $_POST['price'] : 0;
    $quantity = (int) $_POST['quantity'];

    if ($food_id > 0 && $quantity > 0) {

        // If item already in cart, increase quantity
        if (isset($_SESSION['cart'][$food_id])) {
            $_SESSION['cart'][$food_id]['quantity'] += $quantity;
        } else {
            // New item in cart
            $_SESSION['cart'][$food_id] = [
                'food_id'  => $food_id,
                'name'     => $name,
                'price'    => $price,
                'quantity' => $quantity,
            ];
        }
    }
}

// Redirect back to product page or cart
header('Location: cart.php');    // or 'customer_panel.php'
exit;
