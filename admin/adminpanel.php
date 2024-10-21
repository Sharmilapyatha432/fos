<?php
include('../admin/layout/header.php');
include('../admin/layout/sidebar_menu.php');
// include('../admin/layout/footer.php');
include('../database/connection.php');

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
// include('../database/connection.php');

// Fetch orders
$order_query = "SELECT o.order_id, o.customer_id AS cid, o.total_amount, o.delivery_status, o.order_date, 
                od.order_details_id, od.food_id, f.name AS food_name, od.quantity, od.price
                FROM orders o 
                JOIN orderdetails od ON o.order_id = od.order_id
                JOIN fooditem f ON od.food_id = f.food_id
                ORDER BY o.order_id";

$orders = mysqli_query($conn, $order_query);

if (!$orders) {
    // Handle query error 
    die("Query Failed: " . mysqli_error($conn));
}

// include('../admin/layout/header.php');
?>


<link rel="stylesheet" href="../css/admin_table.css">
<div class="main-content">
    <h2 align="center">Orders List</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Item ID</th>
                <th>Created At</th>
                <th>Customer ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($order = mysqli_fetch_assoc($orders)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                <td><?php echo htmlspecialchars($order['order_details_id']); ?></td>
                <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                <td><?php echo htmlspecialchars($order['cid']); ?></td>
                <td><?php echo htmlspecialchars($order['food_id']); ?></td>
                <td><?php echo htmlspecialchars($order['food_name']); ?></td>
                <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                <td><?php echo htmlspecialchars($order['total_amount']); ?></td>
                <td><?php echo htmlspecialchars($order['delivery_status']); ?></td>
                <td> 
                    <form action="update_order_status.php" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                        <select name="delivery_status" onchange="this.form.submit()" class="form-select" <?php if ($order['delivery_status'] == 'canceled' || $order['delivery_status'] == 'delivered') echo 'disabled'; ?>>
                            <option value="" disable selected>Order Status</option>
                            <option value="shipped" <?php if ($order['delivery_status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                            <option value="delivered" <?php if ($order['delivery_status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                        </select>
                    </form>

                    <?php //if ($order['status'] != 'canceled' && $order['status'] != 'delivered'): ?>
                        <?php if ($order['status'] == 'pending'): ?>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelOrderModal" data-order-id="<?php echo htmlspecialchars($order['order_id']); ?>">
                            Cancel Order
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled>
                        <?php echo ($order['delivery_status'] == 'canceled') ? 'Canceled' : 'Cannot be Cancel'; ?>
                        </button>
                    <?php endif; ?>
                    
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>


<!-- Cancel Order Modal -->
<!-- <div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Enter Order ID</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="cancel_order.php" method="post">
                    <div class="form-group">
                        <label for="order_id">Order ID:</label>
                        <input type="number" class="form-control" id="order_id" name="order_id" required>
                    </div>
                    <button type="submit" class="btn btn-danger">Cancel Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $('#cancelOrderModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var orderId = button.data('order-id'); // Extract info from data-* attributes
        var modal = $(this);
        modal.find('#order_id').val(orderId); // Set the order ID in the modal input
    });
</script> -->