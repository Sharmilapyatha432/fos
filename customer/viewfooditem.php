<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// include($_SERVER['DOCUMENT_ROOT'] . '/fos/customer/layout/header.php');
// include($_SERVER['DOCUMENT_ROOT'] . '/fos/customer/layout/sidebar.php');
include($_SERVER['DOCUMENT_ROOT'] . '/fos/customer/layout/layout.php');
include('../database/connection.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch all food items
$sql = "SELECT food_id, name, description, price, image FROM fooditem";
$result = $conn->query($sql);
?>

<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="../css/card.css">

<!-- <div class="container"> -->
    <div class="main-detail">
        <h1 class="main-title">Choose Orders</h1>
        <div class="detail-wrapper">
            <?php if ($result && $result->num_rows > 0) { 
                while ($row = $result->fetch_assoc()) { ?>
                    <div class="detail-card">
                        <img src="<?php echo !empty($row['image']) ? htmlspecialchars($row['image']) : 'path/to/default-image.jpg'; ?>" 
                            class="detail-img" 
                            alt="<?php echo htmlspecialchars($row['name']); ?>" />
                        <div class="detail-desc">
                            <div class="detail-name">
                                <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                                <p class="detail-sub"><?php echo htmlspecialchars($row['description']); ?></p>
                                <p class="price"><strong>Price: NRs.<?php echo htmlspecialchars($row['price']); ?></strong></p>
                                <form method="post" action="checkout.php">
                                    <input type="hidden" name="food_id" value="<?php echo $row['food_id']; ?>">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity:</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" required>
                                    </div>
                                    <button type="submit" name="placeorder" class="btn btn-primary w-100">Place Order</button>
                                </form>
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
 <!-- </div>  Close .container here -->


<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script> -->

<?php //include('customer/layout/cfooter.php'); ?>
