<?php
session_start();
// Database connection
include('../database/connection.php');

// Priority weights
$prepTimeWeight = 0.7;   //i.e. 70% of the priority weight
$distanceWeight = 0.3;   //i.e. 30% of the priority weight

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve input values
    $orderId = intval($_POST['order_id']);
    $prepTime = intval($_POST['prep_time']); // in minutes
    $distance = floatval($_POST['distance']); // in kilometers

    // Apply minimum prep time of 30 minutes
    if ($prepTime < 30) {
        $prepTime = 30;
    }

    // Check if the delivery location is within a 5km radius
    if ($distance <= 5) {
        $targetDeliveryTime = 45; // 45 minutes within 5km radius
    } else {
        // Adjust target delivery time based on distance (can customize as needed)
        $targetDeliveryTime = $prepTime + ($distance * 2); // Simplified adjustment for example
    }

    // Calculate priority score
    $priorityScore = ($prepTimeWeight * $prepTime) + ($distanceWeight * $distance);

    // Insert priority score into notifications table
    $stmt = $conn->prepare("UPDATE notification SET priority_score = ?, target_delivery_time = ? WHERE order_id = ?");
    $stmt->bind_param("dii", $priorityScore, $targetDeliveryTime, $orderId);
    if ($stmt->execute()) {
        echo "Order ID: $orderId has been updated with a priority score of $priorityScore and target delivery time of $targetDeliveryTime minutes.";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
