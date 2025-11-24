<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// $customer_id = $row['customer_id'];

// Ensure the user is logged in
if (!isset($_SESSION['cid'])) {
    header('Location: login.php'); // Redirect to login page if the customer is not logged in
    exit();
}

// $email = $_SESSION['email'];  // Get the customer ID (cid) from the session
$customer_id = $_SESSION['cid']; // or get it from a login system


include('../database/connection.php');  // Database connection
include('../customer/layout/layout.php');  // Layout (header, sidebar, etc.)

// SQL query to fetch the order details for the logged-in customer (based on cid)
$order_query = "SELECT o.order_id, o.total_amount, o.delivery_status,
                od.order_details_id, od.quantity, od.price AS item_price, f.food_id, 
                f.name AS food_name, f.description AS food_description, f.image
                FROM orders o 
                JOIN orderdetails od ON o.order_id = od.order_id
                JOIN fooditem f ON od.food_id = f.food_id
                WHERE o.cid = (SELECT cid FROM Customer WHERE email = ?) 
                ORDER BY o.order_id DESC;";  // Fetch orders for the logged-in customer

// Prepare and execute the query
$stmt = $conn->prepare($order_query);
$stmt->bind_param('s', $email);  // Bind the customer ID to the query (i = integer)
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../css/admin_table.css">
<div class="main-content">
    <h2 style="text-align: center; padding: 20px;">My Orders</h2>
    <div class="table-wrapper">
        <!-- Back to Dashboard Button -->
        <button onclick="window.location.href='viewfooditem.php';" 
        style="padding: 15px 20px; background-color: #4CAF50; color: white; border: none;
        border-radius: 5px; cursor: pointer; text-decoration: none; margin-bottom: 10px;">
        Back to Dashboard
        </button>

    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Food Item</th>
                <th>Food Description</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Amount</th>
                <th>Food Image</th>
                <th>Estimated Delivery Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Check if there are any results
            if ($result->num_rows > 0) {
                while ($order = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                        <td><?php echo htmlspecialchars($order['food_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['food_description']); ?></td>
                        <td><?php echo number_format($order['item_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo number_format($order['quantity'] * $order['item_price'], 2); ?></td>
                        <td><img src="../img/<?php echo htmlspecialchars($order['image']); ?>" alt="Food Image" width="100"></td>
                        <td><?php echo ($order['estimated_delivery_time']); ?></td>
                        <td><?php echo htmlspecialchars($order['delivery_status']); ?></td>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='8' style='text-align: center;'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>
        </div>
    </div>
</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg-com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<!-- adding javascript -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="../../js/app.js"></script>
</body>
</html>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>