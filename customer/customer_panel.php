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

<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


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
                                class="btn btn-warning w-100 open-checkout-modal"
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

<!-- Modal for Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Place Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form method="POST" action="placeorder.php">
            <!-- Hidden Food ID (will be populated by JS) -->
            <input type="hidden" name="food_id" id="food_id">

            <div class="mb-3">
                <label for="order_name" class="form-label">Product Name</label>
                <input type="text" id="order_name" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label for="order_price" class="form-label">Price</label>
                <input type="text" id="order_price" class="form-control" disabled>
            </div>

            <div class="mb-3">
                <label for="order_quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="order_quantity" class="form-control" min="1" required onchange="updateTotalPrice()">
            </div>

            <div class="mb-3">
                <label for="order_total_price" class="form-label">Total Price</label>
                <input type="text" id="order_total_price" class="form-control" disabled>
            </div>

            <!-- Shipping Address -->
            <div class="mb-3">
                <label for="shipping_address" class="form-label">Shipping Address</label>
                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" required></textarea>
            </div>

            <!-- City -->
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" name="city" id="city" class="form-control" required>
            </div>

            <!-- Distance Field -->
            <div class="mb-3">
                <label for="distance_from_restaurant" class="form-label">Distance (km)</label>
                <input type="number" name="distance_from_restaurant" id="distance_from_restaurant" step="0.1" class="form-control" required>
            </div>

            <!-- Payment Method -->
            <div class="mb-3">
                <label for="payment_method" class="form-label">Payment Method</label>
                <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentFields()">
                    <option value="cod">Cash on Delivery</option>
                    <!-- Add more payment options here if needed -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Confirm Order</button>
        </form>
      </div> <!-- close modal-body -->
    </div> <!-- close modal-content -->
  </div> <!-- close modal-dialog -->
</div> <!-- close .modal -->

<?php //include('customer/layout/cfooter.php'); ?>

</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

<!-- Bootstrap bundle (required for modal to work) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Your custom JS -->
<script src="../js/checkout.js"></script>
<script src="../js/app.js"></script>

</html>
