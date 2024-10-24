<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
    <h1 class="mb-4">Available Food Items</h1>
    <div class="row">
        <?php if ($result && $result->num_rows > 0) { 
            while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="<?php echo !empty($row['image']) ? htmlspecialchars($row['image']) : 'path/to/default-image.jpg'; ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($row['name']); ?>" />
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Price: NRs.<?php echo htmlspecialchars($row['price']); ?></strong></p>

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
            </div>
        <?php } 
        } else { ?>
            <div class="col-12">
                <p>No food items available.</p>
            </div>
        <?php } ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<?php //include('customer/layout/cfooter.php'); ?>
