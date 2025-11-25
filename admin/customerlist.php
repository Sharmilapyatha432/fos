

<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminname'])) {
    header('Location: adminlogin.php');
    exit();
}

include('../database/connection.php'); // Include the database connection

//Displaying sucess and error message using sweetalert
// if (isset($_SESSION['message'])) {
//     $message = $_SESSION['message'];
//     $type = $_SESSION['status_type'];
//     echo "
//     <script>
//         Swal.fire({
//             icon: '$type',
//             title: '$message',
//             showConfirmButton: false,
//             timer: 1500
//         });
//     </script>
//     ";
    // Clear the message after displaying it
//     unset($_SESSION['message']);
//     unset($_SESSION['status_type']);
// }

// Handle form submission for updating delivery person status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cid = intval($_POST['cid']);
}
    // $status = $_POST['status'];

    // Update the status of the delivery person
//     $stmt = $conn->prepare("UPDATE Delivery_Person SET status = ? WHERE dpid = ?");
//     $stmt->bind_param('si', $status, $dpid);

//     if ($stmt->execute()) {
//         // Set a success message and refresh the page
//         $_SESSION['message'] = "Status updated successfully.";
//         $_SESSION['status_type'] = "success";
//     } else {
//         $_SESSION['message'] = "Failed to update status";
//         $_SESSION['status_type'] = "error";
//     }
//     header("Location: deliveryperson_list.php"); // Reload the page to clear the form
//     exit();
//     $stmt->close();

//Removing customer from the database
if (isset($_POST['delete'])) {
    $dpid = $_POST['cid'];
    $sql = "DELETE FROM customer WHERE cid='$cid'";
    $result = $conn->query($sql);
    if ($result) {
        header("Location: customer_list.php");
        exit; // Redirect after deletion
    } else {
        die("Error: " . $conn->error);
    }
}

// Fetch all customer from the database
$query = "SELECT cid, name, address, mobile, email FROM customer";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

include('../admin/layout/header.php'); 
// include('../admin/layout/sidebar_menu.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List</title>
</head>
<body>

<link rel="stylesheet" href="../css/admin_table.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="main-content">
    <h2 class="text-center" style="padding:10px">Customer List</h2>

    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>

    <div class="table-wrapper">
    <table class="table">
        <thead>
            <tr>
                <th>CID</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) { 
                while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['cid']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <!-- Removing Customer -->
                        <form method="post" action=" " style="display:inline;">
                                <input type="hidden" value="<?php echo $row['cid']; ?>" name="cid" />
                                <input type="submit" class="btn-danger" value="Remove" name="delete"
                                onclick="return confirm('Are you sure you want to delete this customer?');"
                                style="background-color: red; color: white; border: none; cursor: pointer;" 
                                onmouseover="this.style.backgroundColor='darkred';" 
                                onmouseout="this.style.backgroundColor='red';" />
                        </form>
                    </td>

                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="10" class="text-center">No Customer found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
</div>

</body>
</html>

<?php
// include('../admin/layout/adminfooter.php');
?>
