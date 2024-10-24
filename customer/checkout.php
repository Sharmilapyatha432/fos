<?php
// Start session
session_start();

// Include database connection
include('../database/connection.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in customer's ID and details
// $customer_id = $_SESSION['cid'];
$email = $_SESSION['email'];

// Get food item details passed from the previous page via POST
$food_id = isset($_POST['food_id']) ? intval($_POST['food_id']) : null;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1; // Default quantity is 1

// Fetch food item details from the database
if ($food_id) {
    $query = "SELECT * FROM fooditem WHERE food_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $food_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $food_item = $result->fetch_assoc();

    if (!$food_item) {
        echo "Invalid food item.";
        exit();
    }

    $item_price = $food_item['price'];
    $total_amount = $item_price * $quantity;
} else {
    echo "No food item selected.";
    exit();
}

// Handle form submission (for placing the order)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $address = $_POST['delivery-address']; // Fixing field name
    $city = $_POST['city']; // Fixing field name
    $payment_method = $_POST['payment_method'];

    // // Ensure that $customer_id is correctly retrieved from session
    // if (isset($_SESSION['cid'])) {
    //     $customer_id = $_SESSION['cid']; // Retrieve customer ID from session
    // } else {
    //     // Handle case where customer ID is not set
    //     die("Customer ID not set in session.");
    // }

    // Insert the order into the 'orders' table
    $insert_order = "INSERT INTO orders (cid, total_amount, delivery_status, shipping_address, city, order_date) 
                     VALUES (?, ?, 'Pending', ?, ?, NOW())";
    $stmt = $conn->prepare($insert_order);

    // The number of placeholders (?) matches the number of bound parameters
    $stmt->bind_param("idss", $customer_id, $total_amount, $address, $city);

    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the generated order ID

        // Insert order details into 'orderdetails' table
        $insert_order_details = "INSERT INTO orderdetails (order_id, food_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_order_details);
        $stmt->bind_param("iiid", $order_id, $food_id, $quantity, $item_price);
        $stmt->execute();

        // Redirect to order confirmation or success page
        header("Location: viewfooditem.php?order_id=" . $order_id);
        exit();
    } else {
        $error_message = "Something went wrong while placing the order.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>

        <?php if (isset($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Review the selected food item -->
        <h2>Order Details</h2>
        <table>
            <tr>
                <th>Food Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($food_item['name']); ?></td>
                <td><?php echo htmlspecialchars($quantity); ?></td>
                <td>NRs. <?php echo number_format($item_price, 2); ?></td>
                <td>NRs. <?php echo number_format($total_amount, 2); ?></td>
            </tr>
        </table>

        <!-- Delivery Information and Payment -->
        <h2>Delivery Information</h2>
        <form method="POST" action="checkout.php">
            <input type="hidden" name="food_id" value="<?php echo $food_id; ?>">
            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">

            <label for="delivery-address">Delivery Address:</label>
            <input type="text" name="delivery-address" id="delivery-address" placeholder="Enter delivery address" required>

            <label for="city">City:</label>
            <input type="text" name="city" id="city" placeholder="Enter your city" required>

            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="none" disable>Select Payment Option</option>   
                <option value="Cash">Cash on Delivery</option>
            </select>

            <button type="submit" name="place_order">
                <a href="placeorder.php">Place Order</a>
            </button>
        </form>
    </div>
</body>
</html>
