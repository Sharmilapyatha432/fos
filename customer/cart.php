<?php
session_start();

include('../customer/layout/layout.php'); // Including layout



// Optional: protect page (only logged in users)
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>My Cart</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body { background: var(--softGreenColor) !important; }
        .main { background: var(--softGreenColor) !important; min-height: 100vh; }
        .content-area { padding-top: 16px; }
    </style>
</head>
<body>

<div class="content-area">

<h2 class="mb-3">My Cart</h2>

<?php if (empty($cart)) : ?>
    <p>Your cart is empty.</p>
<?php else : ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price (NRs.)</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($cart as $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($item['name']); ?></td>
                <td><?php echo number_format($item['price'], 2); ?></td>
                <td><?php echo (int)$item['quantity']; ?></td>
                <td><?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <a href="remove_from_cart.php?id=<?php echo (int)$item['food_id']; ?>" class="btn btn-sm btn-danger">
                        Remove
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h4>Total: NRs. <?php echo number_format($total, 2); ?></h4>

    <!-- Proceed to checkout opens modal -->
    <button
        type="button"
        class="btn btn-success"
        id="openCheckout"
        data-bs-toggle="modal"
        data-bs-target="#checkoutModal"
        data-total="<?php echo number_format($total, 2, '.', ''); ?>"
    >
        Proceed to Checkout
    </button>
<?php endif; ?>

<!-- ================= CHECKOUT MODAL (same style as customer_panel.php) ================ -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">Place Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <!-- IMPORTANT:
             In placeorder.php, read items from $_SESSION['cart']
             instead of a single food_id, since this is whole-cart checkout.
        -->
        <form method="POST" action="placeorder.php">

            <!-- If you still want to send total to PHP, you can add a hidden input: -->
            <input type="hidden" name="cart_total" id="cart_total_hidden">

            <!-- You can optionally show "Cart Total" instead of single product -->
            <div class="mb-3">
                <label for="order_total_price" class="form-label">Cart Total (NRs.)</label>
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
                <select id="payment_method" name="payment_method" class="form-select" required>
                    <option value="cod">Cash on Delivery</option>
                    <!-- Add more methods later if needed -->
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Confirm Order</button>
        </form>
      </div> <!-- /modal-body -->
    </div> <!-- /modal-content -->
  </div> <!-- /modal-dialog -->
</div> <!-- /modal -->
<!-- ================================================================================ -->

<!-- Bootstrap bundle + optional jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// When user clicks "Proceed to Checkout", fill the total in modal
document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('openCheckout');
    if (!btn) return;

    btn.addEventListener('click', function () {
        const total = this.getAttribute('data-total') || '0.00';
        const displayTotal = document.getElementById('order_total_price');
        const hiddenTotal  = document.getElementById('cart_total_hidden');

        if (displayTotal) displayTotal.value = total;
        if (hiddenTotal) hiddenTotal.value  = total;
    });
});
</script>

</div>
<?php if (isset($_SESSION['message'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
    icon: "<?php echo $_SESSION['message']['type']; ?>",
    title: "<?php echo ($_SESSION['message']['type'] === 'success') ? 'Success' : 'Error'; ?>",
    html: "<?php echo $_SESSION['message']['text']; ?>",
    confirmButtonText: "OK"
});
</script>
<?php unset($_SESSION['message']); endif; ?>

</body>
</html>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
