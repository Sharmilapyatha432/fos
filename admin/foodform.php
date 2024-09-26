<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetAlert.css">
    <link rel="stylesheet" href="css/myfarm.css"> <!--CSS link for form-->
    <title>Add Food Form</title>
</head>
<body>

<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include Files
include('../admin/layout/header.php');
// include('../admin/layout/sidebar_menu.php');
include('../database/connection.php');

// Fetch food categories from the database
$sql = "SELECT * FROM foodcategories";
$categories = $conn->query($sql);

// Add food items when the form is submitted
if (isset($_POST['submit'])) {
    $foodname = $_POST['farmname'];
    $fooddescription = $_POST['fooddescription'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    // Validate form fields
    if (empty($foodname) || empty($fooddescription) || empty($price) || empty($category_id)) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'All fields are required!',
                showConfirmButton: true,
                confirmButtonText: 'OK',
            });
        </script>
        <?php
    } else {
        // Insert food data into the database with category
        $sql = "INSERT INTO fooditem (name, description, price, category_id) VALUES ('$foodname', '$fooddescription', '$price', '$category_id')";
        $result = $conn->query($sql);

        if ($result) {
            ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Successful',
                    text: 'Food Added!',
                    showConfirmButton: true,
                    confirmButtonText: 'OK',
                }).then(function(){
                    window.location.href = "fooditems.php";
                });
            </script>
            <?php
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error: <?php echo $conn->error; ?>',
                    });
                  </script>";
        }
    }
}

?>


<?php
//Display Foods Under Specific Categories
// Fetch category ID from the URL
$category_id = $_GET['category_id'];

// Fetch food items under the selected category
$sql = "SELECT * FROM fooditem WHERE category_id = $category_id";
$foods = $conn->query($sql);    

if ($foods->num_rows > 0) {
    echo "<h2>Foods under this Category:</h2>";
    while ($row = $foods->fetch_assoc()) {
        echo "<p>" . $row['name'] . " - $" . $row['price'] . "</p>";
    }
} else {
    echo "<p>No food items found under this category.</p>";
}
?>

<!-- HTML form for adding a food item -->
<div class="cont">
    <div id="right">
        <form action="" method="post">
            <h1>Add Food Items</h1>
            <!-- Category dropdown -->
            <div>
                <label for="category_id">Select Category</label>
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php
                    if ($categories->num_rows > 0) {
                        while($row = $categories->fetch_assoc()) {
                            echo "<option value='" . $row['category_id'] . "'>" . $row['category_name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>No Categories Found</option>";
                    }
                    ?>
                </select><br>
            </div>
            <div>
                <label for="foodname" class="fname">Food Name</label>
                <input type="text" name="farmname" required /><br>
                
                <label for="fooddescription" class="fdes">Food Description</label>
                <textarea name="fooddescription" required></textarea><br>
            </div>
            <div>
                <label for="price">Price</label>
                <input type="text" name="price" required /><br>
            </div>


            <input type="submit" value="Add Food" name="submit" />
            <a href="fooditems.php">Back</a>
        </form>
    </div>
</div>
    
</body>
</html>
