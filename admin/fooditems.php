<?php
include('../admin/layout/header.php');

// Check if the user is logged in; redirect to login page if not logged in
session_start();
if (!isset($_SESSION['adminname'])) {
    header("location:adminlogin.php");
}
$adminname = $_SESSION['adminname'];

// include('../admin/layout/sidebar_menu.php');
?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
include("../database/connection.php");

// Delete record if the delete form is submitted
if (isset($_POST['delete'])) {
    $food_id = $_POST['food_id'];
    $sql = "DELETE FROM fooditem WHERE food_id = '$food_id'";
    $result = $conn->query($sql);
    if ($result) {
        echo "<script>alert('Food Item Deleted Successfully');</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all food items (removed the session-based filter)
$sql = "SELECT f.*, fc.category_name FROM fooditem f 
        LEFT JOIN foodcategory fc ON f.category_id = fc.category_id";
$result = $conn->query($sql);
?>

<link rel="stylesheet" href="../css/table.css"> <!--CSS link for table-->
<div class="con">
    <?php if (!isset($_POST['add'])) { ?>
        <h1 align="center" style="padding:10px">Food Item List</h1>
        <div class="table-wrapper">
            <form action="add_fooditem.php" method="post">
                <input type="submit" value="Add Food" name="add">
            </form>
            <table class="fl-table">
                <tbody>
                    <tr>
                        <th>SN</th>
                        <th>Food ID</th>
                        <th>Food Category</th>
                        <th>Food Name</th>
                        <th>Food Description</th>
                        <th>Food Price</th>
                        <th>Action</th>
                    </tr>
                    <?php if ($result && $result->num_rows > 0) {
                        $i = 1;
                        while ($row = $result->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row['food_id']; ?></td>
                                <td><?php echo $row['category_name']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['price']; ?></td>
                                <td>
                                    <div class="button-row">
                                        <form method="post" action="food_edit.php">
                                            <input type="hidden" value="<?php echo $row['food_id']; ?>" name="food_id" />
                                            <input type="submit" value="Edit" name="edit" />
                                        </form>
                                        <form method="post" action="fooditems.php">
                                            <input type="hidden" value="<?php echo $row['food_id']; ?>" name="food_id" />
                                            <input type="submit" value="Delete" name="delete" />
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php }
                    } else { ?>
                        <tr>
                            <td colspan="7">No Food Items found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>

