<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Ensure customer_id is in the session
// if (!isset($_SESSION['cid'])) {
//     die('Customer ID not set in session.');
// }

include('../database/connection.php'); // Database connection
include('../customer/layout/layout.php');
// include('../customer/layout/header.php');
// include('../customer/layout/sidebar.php');


// Get customer ID from the session
$email = $_SESSION['email'];

// SQL query to fetch the order details for the logged-in customer
$order_query = "SELECT o.order_id, o.total_amount, o.delivery_status, 
                od.order_details_id, od.quantity, od.price AS item_price, f.food_id, 
                f.name AS food_name, f.description AS food_description, f.image
                FROM orders o 
                JOIN orderdetails od ON o.order_id = od.order_id
                JOIN fooditem f ON od.food_id = f.food_id
                WHERE o.cid = ? ORDER BY o.order_id DESC;";

$stmt = $conn->prepare($order_query);
$stmt->bind_param('i', $email); // Bind customer ID to the query
$stmt->execute();
$result = $stmt->get_result();
?>


<div class="my-orders">
    <h2 style="text-align: center; padding: 20px;">My Orders</h2>
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
                <!-- <th>Order Date</th> -->
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($order['food_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['food_description']); ?></td>
                    <td><?php echo number_format($order['item_amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td><?php echo number_format($order['quantity'] * $order['item_amount'], 2); ?></td>
                    <td><img src="<?php echo htmlspecialchars($order['image']); ?>" alt="Food Image" width="100"></td>
                    <!-- <td><?php //echo date('d-m-Y', strtotime($order['order_date'])); ?></td> -->
                    <td><?php echo htmlspecialchars($order['delivery_status']); ?></td>
                    <td>
                        <?php //if ($order['status'] == 'pending'): ?>
                            <!-- <button type="button" class="btn btn-danger cancelOrderBtn" data-orderid="<?php //echo htmlspecialchars($order['order_id']); ?>">
                                Cancel Order
                            </button> -->
                        <?php //elseif ($order['status'] == 'delivered'): ?>
                            <!-- <button class="btn btn-success" disabled>Delivered</button> -->
                        <?php //else: ?>
                            <!-- <button type="button" class="btn btn-secondary" disabled>Cancel Order</button> -->
                        <?php //endif; ?>
                        <!-- <br><br> -->
                        <!-- Give Review Form -->
                        <form method="post" action="review.php?product_id=<?php echo htmlspecialchars($order['product_id']); ?>">
                            <input type="hidden" value="<?php echo htmlspecialchars($order['order_id']); ?>" name="orderid" />
                            <input type="submit" value="Give Review" name="give_review" 
                            style="background-color: #07b934; color: white; border-radius: 5px;
                            border: none; padding: 10px 15px; cursor: pointer;"
                            onmouseover="this.style.backgroundColor='#04a004';"
                            onmouseout="this.style.backgroundColor='#07b934';"
                            <?php if ($order['status'] != 'delivered') echo 'disabled'; ?> />
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<button>
    <a href="viewfooditem.php">Back to Dashboard</a>
</button>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>