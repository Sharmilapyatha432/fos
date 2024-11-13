<?php
session_start();
include('../database/connection.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// $customer_id = $_SESSION['cid'];
$email = $_SESSION['email']; // Fetch logged-in user email

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['food_id'], $_POST['quantity'])) {
        $food_id = intval($_POST['food_id']);
        $quantity = intval($_POST['quantity']);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Fetch food details (price) to calculate total and check availability
            $stmt = $conn->prepare("SELECT price FROM fooditem WHERE food_id = ?");
            $stmt->bind_param("i", $food_id);
            $stmt->execute();
            $food = $stmt->get_result()->fetch_assoc();

            // Ensure the food exists
            if ($food) {
                $total_amount = $food['price'] * $quantity;

                // Insert the order into the orders table
                $sql_order = "INSERT INTO orders (customer_id, total_amount, delivery_status) VALUES (?, ?, 'pending')";
                $stmt_order = $conn->prepare($sql_order);
                $stmt_order->bind_param("id", $customer_id, $total_amount);
                $stmt_order->execute();

                // Get the last inserted order ID
                $order_id = $conn->insert_id;

                // Insert order details into order_items table
                $sql_order_item = "INSERT INTO orderdeatils (order_id, food_id, quantity, price) 
                                   VALUES (?, ?, ?, ?)";
                $stmt_order_item = $conn->prepare($sql_order_item);
                $stmt_order_item->bind_param("iiid", $order_id, $food_id, $quantity, $food['price']);
                $stmt_order_item->execute();

                // <?php
                // function addOrderNotification($orderId, $customerName) {
                //     $message = "New order received from $customerName. Order ID: $orderId. Please come and receive the order for delivery.";
                    // $conn = new mysqli('localhost', 'username', 'password', 'database');
                    
                //     $stmt = $conn->prepare("INSERT INTO notifications (order_id, message) VALUES (?, ?)");
                //     $stmt->bind_param("is", $orderId, $message);
                //     $stmt->execute();
                //     $stmt->close();
                //     $conn->close();
                // }

// Example usage after an order is placed:
// addOrderNotification(12345, "John Doe");



                // Commit the transaction
                $conn->commit();

                // Set success message in session
                $_SESSION['message'] = [
                    'type' => 'success',
                    'text' => 'Your order has been placed successfully.'
                ];
            } else {
                // Rollback transaction if stock is insufficient
                $conn->rollback();
                $_SESSION['message'] = [
                    'type' => 'error',
                    'text' => 'Food not available.'
                ];
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'An error occurred while placing the order. Please try again.'
            ];
        }

        // Redirect back to view_product.php
        header("Location: viewfooditem.php");
        exit();
    }
}