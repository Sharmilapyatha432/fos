document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.open-checkout-modal');

    buttons.forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.getAttribute('data-food-id');
            const name = this.getAttribute('data-food-name');
            const price = this.getAttribute('data-food-price');

            document.getElementById('food_id').value = id;
            document.getElementById('order_name').value = name;
            document.getElementById('order_price').value = price;
            document.getElementById('order_quantity').value = 1;

            // Initialize total price
            updateTotalPrice();
        });
    });
});

function updateTotalPrice() {
    const price = parseFloat(document.getElementById('order_price').value || 0);
    const qty = parseInt(document.getElementById('order_quantity').value || 1, 10);
    document.getElementById('order_total_price').value = (price * qty).toFixed(2);
}

function togglePaymentFields() {
    // Add logic if you add more payment methods later
}
