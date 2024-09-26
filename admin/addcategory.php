<?php
session_start();
include('../database/connection.php'); //Database Connection


if (!isset($_SESSION['adminname'])) {
    header("Location: adminlogin.php");
}

//Add Product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $query = "INSERT INTO foodcategory (name) VALUES ('$name')";
    mysqli_query($conn, $query);
    header("Location: adminpanel.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00636E;
        }
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button[type="submit"] {
            background-color: #00636E;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button[type="submit"]:hover {
            background-color: #004d58;
        }
        .back-button a {
            color: white;
            text-decoration: none;
        }
        .back-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Add Category</h2>
        <form method="post" action="addcategory.php">
            <div class="mb-3">
                <label for="category">Category Name:</label>
                <input type="text" name="name" placeholder="Enter Category Name" required>
            </div>
            <button type="submit">Add Category</button>
            <div class="back-button">
                <a href="adminpanel.php">Back</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
