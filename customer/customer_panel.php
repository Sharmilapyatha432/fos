<?php
session_start();
// Enable error reporting for debugging  
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('../customer/layout/layout.php'); // Including layout
include('../database/connection.php');    // Including database connection

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email']; // Fetch logged-in user email

// Fetch all food items
$sql = "SELECT food_id, name, description, price, image FROM fooditem";
$result = $conn->query($sql);
?>

<!-- Link to Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../css/card.css">
<style>
    html, body { background: var(--softGreenColor) !important; }
    .main { background: var(--softGreenColor) !important; min-height: 100vh; }
    .main-detail, .detail-wrapper { background: transparent; }
</style>

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


<div class="content-area">
<div class="main-detail">
    <h1 class="main-title">Choose Orders</h1>
    <div class="detail-wrapper">
        <?php if ($result && $result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { ?>
                <div class="detail-card">
                    <img src="../img/<?php echo !empty($row['image']) ? htmlspecialchars($row['image']) : 'path/to/default-image.jpg'; ?>" 
                        class="detail-img" 
                        alt="<?php echo htmlspecialchars($row['name']); ?>" />
                    <div class="detail-desc">
                        <div class="detail-name">
                            <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                            <p class="detail-sub"><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="price">
                                <strong>Price: NRs.<?php echo htmlspecialchars($row['price']); ?></strong>
                            </p>

                             <!-- ADD TO CART FORM -->
                            <form method="post" action="add_to_cart.php">
                <input type="hidden" name="food_id" value="<?php echo (int)$row['food_id']; ?>">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($row['price']); ?>">

                <div class="mb-3">
                    <label for="qty_<?php echo $row['food_id']; ?>" class="form-label">Quantity:</label>
                    <input
                        type="number"
                        name="quantity"
                        id="qty_<?php echo $row['food_id']; ?>"
                        class="form-control"
                        value="1"
                        min="1"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-warning w-100">
                    Add to Cart
                </button>
            </form>
            <!-- /ADD TO CART FORM -->
<br>

                            <!-- Quantity just for display; order is done via modal -->
                            <!-- <div class="mb-3">
                                <label for="quantity_<?php echo $row['food_id']; ?>" class="form-label">Quantity:</label>
                                <input 
                                    type="number" 
                                    id="quantity_<?php echo $row['food_id']; ?>" 
                                    class="form-control" 
                                    value="1" 
                                    min="1">
                            </div> -->

                            <!-- Button triggers the modal and passes data via data-* attributes -->
                            <button 
                                type="button" 
                                class="btn btn-primary w-100 open-checkout-modal"
                                data-bs-toggle="modal" 
                                data-bs-target="#checkoutModal"
                                data-food-id="<?php echo $row['food_id']; ?>"
                                data-food-name="<?php echo htmlspecialchars($row['name']); ?>"
                                data-food-price="<?php echo htmlspecialchars($row['price']); ?>"
                            >
                                Place Order
                            </button>
                        </div>
                    </div>
                </div> <!-- Close .detail-card here -->
            <?php } ?>
        <?php } else { ?>
            <div class="col-12">
                <p>No food items available.</p>
            </div>
        <?php } ?>
    </div> <!-- Close .detail-wrapper here -->
</div> <!-- Close .main-detail here -->
</div>

<!-- Modal for Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Place Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form method="POST" action="placeorder.php" class="row g-3">
            <!-- Hidden Food ID (will be populated by JS) -->
            <input type="hidden" name="food_id" id="food_id">

            <div class="col-md-6">
                <label for="order_name" class="form-label">Product Name</label>
                <input type="text" id="order_name" class="form-control" disabled>
            </div>

            <div class="col-md-3">
                <label for="order_price" class="form-label">Price</label>
                <input type="text" id="order_price" class="form-control" disabled>
            </div>

            <div class="col-md-3">
                <label for="order_quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="order_quantity" class="form-control" min="1" required onchange="updateTotalPrice()">
            </div>

            <div class="col-md-4">
                <label for="order_total_price" class="form-label">Total Price</label>
                <input type="text" id="order_total_price" class="form-control" disabled>
            </div>

            <div class="col-md-8">
                <label for="shipping_address" class="form-label">Shipping Address</label>
                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" required></textarea>
            </div>

            <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="distance_from_restaurant" class="form-label">Distance (km)</label>
                <input type="number" name="distance_from_restaurant" id="distance_from_restaurant" step="0.1" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentFields()">
                    <option value="cod">Cash on Delivery</option>
                    <!-- Add more payment options here if needed -->
                </select>
            </div>

            <div class="col-12">
                <!-- <button type="submit" class="btn btn-primary w-100">Confirm Order</button> -->
                <button type="button" class="btn btn-primary w-100" onclick="confirmOrderPopup()">Confirm Order</button>
            </div>
        </form>
      </div> <!-- close modal-body -->
    </div> <!-- close modal-content -->
  </div> <!-- close modal-dialog -->
</div> <!-- close .modal -->

<?php //include('customer/layout/cfooter.php'); ?>

<!-- JS LIBRARIES (keep these first) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/checkout.js"></script>
<script src="../js/app.js"></script>

<!-- YOUR SWEETALERT CONFIRM BEFORE SUBMIT -->
<script>
function confirmOrderPopup() {
    let distance = parseFloat(document.getElementById("distance_from_restaurant").value);
    let qty = parseInt(document.getElementById("order_quantity").value);
    let price = parseFloat(document.getElementById("order_price").value);

    if (!distance || !qty || !price) {
        Swal.fire("Error", "Please fill all required fields.", "error");
        return;
    }

    let eta = distance <= 5 ? 45 : 45 + ((distance - 5) * 5);
    let total = qty * price;

    Swal.fire({
        title: "Confirm Order?",
        html: `
            <b>Total Price:</b> NRs. ${total}<br>
            <b>Estimated Delivery Time:</b> ${eta} minutes
        `,
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Place Order",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            document.querySelector("#checkoutModal form").submit();
        }
    });
}
</script>

<!-- SWEETALERT AFTER REDIRECT -->
<?php if (isset($_SESSION['message'])): ?>
<script>
Swal.fire({
    icon: "<?php echo $_SESSION['message']['type']; ?>",
    title: "<?php echo ($_SESSION['message']['type'] === 'success') ? 'Success' : 'Error'; ?>",
    html: "<?php echo $_SESSION['message']['text']; ?>",
    confirmButtonText: "OK"
});
</script>
<?php unset($_SESSION['message']); endif; ?>

</html>
