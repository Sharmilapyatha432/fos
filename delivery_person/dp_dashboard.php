<?php
session_start();

// Enable error reporting for debugging  
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../database/connection.php'); // Including database connection
include('../delivery_person/layout/dheader.php');
// include('../delivery_person/layout/sidebar.php');

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email']; // Fetch logged-in user email

$sql = "SELECT o.order_id, o.cid, o.order_date, o.total_amount, o.shipping_address, o.payment_method, o.delivery_status, c.name AS customer_name
        FROM orders o
        JOIN customer c ON o.cid = c.cid
        WHERE o.delivery_status = 'Pending'";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if the form is submitted to update the delivery status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['delivery_status'])) {
    $order_id = $_POST['order_id'];
    $delivery_status = $_POST['delivery_status'];
    
    // Update the delivery status in the database
    $update_sql = "UPDATE orders SET delivery_status = ? WHERE order_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $delivery_status, $order_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Order status updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating order status.');</script>";
    }
    
    $update_stmt->close();
}

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
        <h1 class="text-center">Your Current Orders</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="table table-bordered table-striped mt-4">
                <thead class="thead-dark">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer Name</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                        <th>Shipping Address</th>
                        <th>Delivery Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['shipping_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['delivery_status']); ?></td>
                            <td>
                                <!-- Action Dropdown -->
                                <form method="post" action="">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                                    <select name="delivery_status" class="form-select" required>
                                        <option value="Pending" <?php echo ($row['delivery_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Delivered" <?php echo ($row['delivery_status'] == 'Delivered') ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary mt-2">Update Status</button>
                                </form>
                            </td>
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



?>




<!------------------ FRONTEND ------------------->
<!-- <!DOCTYPE html>
<html lang="en">
<head> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Dashboard</title>
</head>
<body> -->

     <!-- Display Welcome Message -->
     <!-- <h2 align="center" style="padding:15px">Welcome, <?= htmlspecialchars($email); ?></h2> -->

    
    

    <!-- Option 1: Bootstrap Bundle with Poppe -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html> -->