<?php
session_start();

// Check if delivery person is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('../database/connection.php');
include('../delivery_person/layout/dheader.php');
// include('../delivery_person/layout/sidebar.php');

// Get delivery person's ID from session
// $dpid = $_SESSION['dpid'];
$dpid = $_SESSION['email'];

// SQL query to fetch orders assigned to the logged-in delivery person where delivery_status is 'Pending' or 'Shipped'
// $sql = "
//     SELECT o.order_id, o.customer_id, o.order_date, o.total_amount, o.delivery_status 
//     FROM Orders o
//     JOIN DeliveryAssignment da ON o.order_id = da.order_id
//     WHERE da.dpid = ? AND o.delivery_status IN ('Shipped')
//     ORDER BY o.order_date DESC";  // Orders will be shown with the latest first

$sql = "SELECT o.order_id, o.cid, o.order_date, o.total_amount, o.shipping_address, o.payment_method, o.delivery_status, c.name AS customer_name
        FROM orders o
        JOIN customer c ON o.cid = c.cid
        WHERE o.delivery_status = 'Delivered'";
$stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $dpid);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Delivered Orders</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <!-- <th>Total Amount</th> -->
                        <th>Shipping Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <!-- <td><?php //echo htmlspecialchars($row['total_amount']); ?></td> -->
                            <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">You have no new or pending orders.</p>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
