<?php
session_start();
include('../database/connection.php'); // Database Connection
include('../admin/layout/header.php'); // Database Connection

if (!isset($_SESSION['adminname'])) {
    header("Location: adminlogin.php");
    exit();
}

// Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['category_name']) && !empty(trim($_POST['category_name']))) {
        $name = trim($_POST['category_name']);
        
        // Check if the category already exists
        $checkQuery = $conn->prepare("SELECT * FROM foodcategory WHERE category_name = ?");
        $checkQuery->bind_param("s", $name);
        $checkQuery->execute();
        $result = $checkQuery->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('Category already exists.');</script>";
        } else {
            // Prepare and execute the insert statement
            $insertQuery = $conn->prepare("INSERT INTO foodcategory (category_name) VALUES (?)");
            $insertQuery->bind_param("s", $name);
            if ($insertQuery->execute()) {
                header("Location: categories.php");
                exit();
            } else {
                echo "<script>alert('Error adding category.');</script>";
            }
        }

        $checkQuery->close();
        $insertQuery->close();
    } 
    // else {
    //     echo "<script>alert('Category name cannot be empty.');</script>";
    // }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/adminpanel.css">
<link rel="stylesheet" href="../css/form.css">
</head>
<body>

    <div class="form-shell">
        <div class="form-card">
            <h2>Add Category</h2>
            <form method="post" action="addcategory.php" class="form-grid">
                <div class="form-field">
                    <label for="category_name">Category Name</label>
                    <input type="text" id="category_name" name="category_name" placeholder="Enter category name" required>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary-solid">Add Category</button>
                    <button type="button" class="btn-ghost" onclick="window.location.href='categories.php'">Back</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
