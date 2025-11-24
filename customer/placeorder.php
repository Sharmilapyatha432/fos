<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css"> -->
<?php
session_start();
include('../database/connection.php');

// Check if user is logged in
if (!isset($_SESSION['cid'])) {
    header("Location: login.php");
    exit();
}

// $email = $_SESSION['email']; // Fetch logged-in user email
$customer_id = $_SESSION['cid']; // or get it from a login system


// Retrieve customer ID based on the email
// $stmt = $conn->prepare("SELECT cid FROM customer WHERE email = ?");
// $stmt->bind_param("s", $email);
// $stmt->execute();
// $result = $stmt->get_result();
// $customer = $result->fetch_assoc();
// $customer_id = $customer['cid'] ?? null;
// // // echo"$customer_id";
// // Redirect if customer not found
// if (!$customer_id) {
//     header("Location: login.php");
//     exit();
// }

// Function to add order notification
// function addOrderNotification($conn, $orderId, $customerEmail) {
//     //  Fetch customer name from the email
//     $stmt = $conn->prepare("SELECT name FROM customer WHERE email = ?");
//     $stmt->bind_param("s", $customerEmail);
//     $stmt->execute();
//     $customer = $stmt->get_result()->fetch_assoc();
//     $customerName = $customer['name'] ?? 'Customer';  // Default to 'Customer' if not found

//     //  Prepare notification message
//     $message = "New order received from $customerName. Order ID: $orderId. Please come and receive the order for delivery.";
    
//     //  Insert notification into the notifications table
//     $stmt = $conn->prepare("INSERT INTO notifications (order_id, message) VALUES (?, ?)");
//     $stmt->bind_param("is", $orderId, $message);
//     $stmt->execute();
//     $stmt->close();
// }


// Handle order placement
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /* ===================== CASE 1: SINGLE ITEM ORDER (from viewfooditem / customer_panel) ===================== */
    if (isset($_POST['food_id']) && isset($_POST['quantity']) && isset($_POST['shipping_address']) && isset($_POST['city']) && isset($_POST['distance_from_restaurant'])) {
        
        // Sanitize and validate inputs
        // echo "hfjsdhfkjsdhfkjdfhgkjdfs fbgkdfhsjkghsf sdbgjfhk";
        $food_id = filter_var($_POST['food_id'], FILTER_VALIDATE_INT);
        $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
        $shipping_address = htmlspecialchars(trim($_POST['shipping_address']));
        $city = htmlspecialchars(trim($_POST['city']));
        $distance_from_restaurant = filter_var($_POST['distance_from_restaurant'], FILTER_VALIDATE_FLOAT);

        if ($food_id === false || $quantity === false || empty($shipping_address) || empty($city)  || $distance_from_restaurant === false) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Invalid input data. Please check the form and try again.'
            ];
            header("Location: viewfooditem.php");
            exit();
        }
        // echo "$food_id";

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Fetch food details (price)
            $stmt = $conn->prepare("SELECT price FROM fooditem WHERE food_id = ?");
            $stmt->bind_param("i", $food_id);
            $stmt->execute();
            $food = $stmt->get_result()->fetch_assoc();

            // Ensure the food exists
            if ($food) {
                $total_amount = $food['price'] * $quantity;

                // Set the estimated delivery time (prep time + distance-based adjustment)
                $prep_time = 30; // Base preparation time in minutes
                $delivery_time = ($distance_from_restaurant <= 5) ? 45 : 45 + ($distance_from_restaurant - 5) * 5;   //For each additional kilometer, an additional 5 minutes is added.

                // Insert the order into the orders table
                $sql_order = "INSERT INTO orders (cid, total_amount, shipping_address, city, delivery_status, distance, estimated_delivery_time)
                                VALUES (?, ?, ?, ?, 'pending', ?, ?)";
                $stmt_order = $conn->prepare($sql_order);
                $stmt_order->bind_param("idssdi", $customer_id, $total_amount, $shipping_address, $city, $distance_from_restaurant, $delivery_time); // Match the parameters correctly
                $stmt_order->execute();

                // Get the last inserted order ID
                $order_id = $conn->insert_id;

                // Insert order details into orderdetails table
                $sql_order_item = "INSERT INTO orderdetails (order_id, food_id, quantity, price) 
                                VALUES (?, ?, ?, ?)";
                $stmt_order_item = $conn->prepare($sql_order_item);
                $stmt_order_item->bind_param("iiid", $order_id, $food_id, $quantity, $food['price']);
                $stmt_order_item->execute();

                // Add order notification for delivery person
                // addOrderNotification($conn, $order_id, $email);

                // Commit the transaction
                $conn->commit();

                // Set success message in session
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Your order has been placed successfully. Estimated delivery time: ' . $delivery_time . ' minutes.'
                ];
            } else {
                // Rollback transaction if food item is not available
                $conn->rollback();
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => 'Food not available.'
                ];
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            // Log error
            error_log("Error placing order: " . $e->getMessage());
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'An error occurred while placing the order. Please try again.'
            ];
        }

        // Redirect back to view_food_item.php
        header("Location: viewfooditem.php");
        exit();
    }

    /* ===================== CASE 2: CART CHECKOUT (from cart.php modal) ===================== */
    elseif (isset($_POST['cart_total']) && isset($_POST['shipping_address']) && isset($_POST['city']) && isset($_POST['distance_from_restaurant'])) {

        // Sanitize and validate inputs
        $shipping_address = htmlspecialchars(trim($_POST['shipping_address']));
        $city = htmlspecialchars(trim($_POST['city']));
        $distance_from_restaurant = filter_var($_POST['distance_from_restaurant'], FILTER_VALIDATE_FLOAT);

        if (empty($shipping_address) || empty($city) || $distance_from_restaurant === false) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Invalid input data. Please check the form and try again.'
            ];
            header("Location: cart.php");
            exit();
        }

        // Ensure cart exists and is not empty
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Your cart is empty.'
            ];
            header("Location: cart.php");
            exit();
        }

        // Recalculate total from session cart (more reliable than trusting POST)
        $total_amount = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Set the estimated delivery time (same algorithm)
            $prep_time = 30; // Base preparation time in minutes
            $delivery_time = ($distance_from_restaurant <= 5) ? 45 : 45 + ($distance_from_restaurant - 5) * 5;   //For each additional kilometer, an additional 5 minutes is added.

            // Insert the order into the orders table
            $sql_order = "INSERT INTO orders (cid, total_amount, shipping_address, city, delivery_status, distance, estimated_delivery_time)
                            VALUES (?, ?, ?, ?, 'pending', ?, ?)";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->bind_param("idssdi", $customer_id, $total_amount, $shipping_address, $city, $distance_from_restaurant, $delivery_time);
            $stmt_order->execute();

            // Get the last inserted order ID
            $order_id = $conn->insert_id;

            // Insert each cart item into orderdetails
            $sql_order_item = "INSERT INTO orderdetails (order_id, food_id, quantity, price) 
                               VALUES (?, ?, ?, ?)";
            $stmt_order_item = $conn->prepare($sql_order_item);

            foreach ($_SESSION['cart'] as $item) {
                $food_id = (int)$item['food_id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];

                $stmt_order_item->bind_param("iiid", $order_id, $food_id, $quantity, $price);
                $stmt_order_item->execute();
            }

            // Commit the transaction
            $conn->commit();

            // Clear cart after successful checkout
            unset($_SESSION['cart']);

            // Set success message in session
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Your order has been placed successfully. Estimated delivery time: ' . $delivery_time . ' minutes.'
            ];

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            // Log error
            error_log("Error placing order (cart): " . $e->getMessage());
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'An error occurred while placing the order. Please try again.'
            ];
        }

        // Redirect back to cart.php after cart checkout
        header("Location: cart.php");
        exit();
    }
}

?>
