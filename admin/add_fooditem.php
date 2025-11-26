<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Food Items</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/adminpanel.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>
<body>

<?php
session_start();
include('../database/connection.php');

if (!isset($_SESSION['adminname'])) {
    header("Location: admin_login.php");
    exit;
}

// Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if all expected form fields are set
    if (isset($_POST['name'], $_POST['description'], $_POST['price'], $_POST['category_id'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $category_id = $_POST['category_id'];

        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../img/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $allowed_file_types = array('jpg', 'jpeg', 'png', 'gif');
            if (in_array($imageFileType, $allowed_file_types)) {
                if (file_exists($target_file)) {
                    $target_file = $target_dir . time() . "_" . basename($_FILES["image"]["name"]);
                }

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $query = "INSERT INTO fooditem (name, description, price, category_id, image) 
                              VALUES ('$name', '$description', '$price', '$category_id', '$target_file')";

                    if (mysqli_query($conn, $query)) {
                        // Set success message
                        $_SESSION['message'] = "Food added successfully!";
                        $_SESSION['msg_type'] = "success"; // or any other type you want to define
                        // header("Location: view_product.php");
                        // exit;
                    } else {
                        // Set error message
                        $_SESSION['message'] = "Error inserting food item: " . mysqli_error($conn);
                        $_SESSION['msg_type'] = "error";
                    }
                } else {
                    $_SESSION['message'] = "Error uploading the image.";
                    $_SESSION['msg_type'] = "error";
                }
            } else {
                $_SESSION['message'] = "Only JPG, JPEG, PNG & GIF files are allowed.";
                $_SESSION['msg_type'] = "error";
            }
        } else {
            $_SESSION['message'] = "Image file is required.";
            $_SESSION['msg_type'] = "error";
        }
    }
}
?>

<?php if (isset($_SESSION['message'])): ?>
        <script>
            swal({
                title: "<?php echo $_SESSION['msg_type'] == 'success' ? 'Success' : 'Error'; ?>",
                text: "<?php echo $_SESSION['message']; ?>",
                icon: "<?php echo $_SESSION['msg_type'] == 'success' ? 'success' : 'error'; ?>",
                button: "OK",
            }).then(() => {
                <?php if ($_SESSION['msg_type'] == 'success'): ?>
                    window.location.href = "fooditems.php";
                <?php endif; ?>
            });
        </script>
        <?php unset($_SESSION['message']); ?>
        <?php unset($_SESSION['msg_type']); ?>
    <?php endif; ?>

<div class="form-shell">
    <div class="form-card">
        <h2>Add Food Item</h2>
        <form method="post" action="add_fooditem.php" enctype="multipart/form-data" autocomplete="off" class="form-grid">
            <div class="form-field">
                <label for="name">Food Name</label>
                <input type="text" id="name" name="name" placeholder="Enter food name" required>
            </div>
            <div class="form-field">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" placeholder="Price" required>
            </div>
            <div class="form-field">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="" disabled selected>Select Category</option>
                    <?php
                    $category_query = "SELECT * FROM foodcategory";
                    $categories = mysqli_query($conn, $category_query);
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo "<option value='{$category['category_id']}'>{$category['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-field" style="grid-column: 1 / -1;">
                <label for="description">Food Description</label>
                <textarea id="description" name="description" placeholder="Enter food description"></textarea>
            </div>
            <div class="form-field">
                <label for="image">Food Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
                <span class="hint">JPG, JPEG, PNG, GIF allowed.</span>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary-solid">Add Product</button>
                <button type="button" class="btn-ghost" onclick="window.location.href='fooditems.php'">Back</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
