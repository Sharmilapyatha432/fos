// Set product details in the modal
    function setProductDetails(food_id, name, price) {
        // Set the values for the modal
        document.getElementById('food_id').value = food_id;
        document.getElementById('order_name').value = name;
        document.getElementById('order_price').value = price;

        // Calculate total price (initially price * quantity)
        updateTotalPrice();

        // Show the modal using Bootstrap's Modal API
        var myModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
        myModal.show();
    }

    // Update total price when quantity changes
    function updateTotalPrice() {
        var price = parseFloat(document.getElementById('order_price').value);
        var quantity = parseInt(document.getElementById('order_quantity').value);
        var totalPrice = price * quantity;
        document.getElementById('order_total_price').value = totalPrice.toFixed(2);
    }

    // Toggle payment fields based on selection (future feature, optional)
    function togglePaymentFields() {
        var paymentMethod = document.getElementById('payment_method').value;
        if (paymentMethod === "cod") {
            // You can add logic to show/hide payment fields based on selected method
        }
    }