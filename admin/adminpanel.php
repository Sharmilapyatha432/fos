<?php
include('../admin/layout/header.php');
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();  // Start the session
if (!isset($_SESSION['adminname'])) {
    // If admin is not logged in, redirect to the login page
    header("Location: adminlogin.php");
    exit();
}

// Database Connection
include('../database/connection.php');

// Fetch orders
// $order_query = "SELECT o.order_id, o.cid, o.total_amount, o.delivery_status, o.order_date, od.order_details_id, 
//     od.food_id, f.name AS food_name, od.quantity, od.price FROM orders o
//     JOIN orderdetails od ON o.order_id = od.order_id
//     JOIN fooditem f ON od.food_id = f.food_id
//     ORDER BY o.order_id";

$order_query = " SELECT o.order_id, o.cid, c.name AS customer_name, o.total_amount, o.delivery_status,
                o.order_date, od.order_details_id, od.food_id, f.name AS food_name, od.quantity, od.price 
                FROM orders o JOIN orderdetails od ON o.order_id = od.order_id
                JOIN fooditem f ON od.food_id = f.food_id
                JOIN customer c ON o.cid = c.cid
                ORDER BY o.order_id;";



// Old Query
// $order_query = "SELECT o.order_id, o.cid AS customer_id, o.total_amount, o.delivery_status, o.order_date, 
//                 od.order_details_id, od.food_id, f.name AS food_name, od.quantity, od.price
//                 FROM orders o 
//                 JOIN orderdetails od ON o.order_id = od.order_id
//                 JOIN fooditem f ON od.food_id = f.food_id
//                 ORDER BY o.order_id";

$orders = mysqli_query($conn, $order_query);

if (!$orders) {
    // Handle query error 
    die("Query Failed: " . mysqli_error($conn));
}
?>

<link rel="stylesheet" href="../css/admin_table.css">
<!-- <link rel="stylesheet" href="../css/adminpanel.css"> -->
<div class="main-content">
    <h2 align="center">Orders List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Item ID</th>
                <th>Created At</th>
                <th>Customer Name</th>
                <th>Food Item ID</th>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
            <tr>
            <td><?php echo htmlspecialchars($order['order_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['order_details_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['order_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['customer_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['food_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['food_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['quantity'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['total_amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($order['delivery_status'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td> 
            <!-- <td> 
                <form action="order_status.php" method="POST">
                    <input type="hidden" name="order_id" value="<?php //echo htmlspecialchars($order['order_id']); ?>">
                    <select name="delivery_status" onchange="this.form.submit()" class="form-select" <?php //if ($order['delivery_status'] == 'delivered') echo 'disabled'; ?>>
                        <option value="" disabled selected>Order Status</option>
                        <option value="shipped" <?php //if ($order['delivery_status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                        <option value="delivered" <?php //if ($order['delivery_status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                    </select>
                </form>
            </td> -->
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>