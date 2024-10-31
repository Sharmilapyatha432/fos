

<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['adminname'])) {
    header('Location: adminlogin.php');
    exit();
}

include('../database/connection.php'); // Include the database connection

//Displaying sucess and error message using sweetalert
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $type = $_SESSION['status_type'];
    echo "
    <script>
        Swal.fire({
            icon: '$type',
            title: '$message',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    ";
    // Clear the message after displaying it
    unset($_SESSION['message']);
    unset($_SESSION['status_type']);
}

// Handle form submission for updating delivery person status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dpid'], $_POST['status'])) {
    $dpid = intval($_POST['dpid']);
    $status = $_POST['status'];

    // Update the status of the delivery person
    $stmt = $conn->prepare("UPDATE Delivery_Person SET status = ? WHERE dpid = ?");
    $stmt->bind_param('si', $status, $dpid);

    if ($stmt->execute()) {
        // Set a success message and refresh the page
        $_SESSION['message'] = "Status updated successfully.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to update status";
        $_SESSION['status_type'] = "error";
    }
    header("Location: deliveryperson_list.php"); // Reload the page to clear the form
    exit();
    $stmt->close();
}

//Deleting delivery person from the database
if (isset($_POST['delete'])) {
    $dpid = $_POST['dpid'];
    $sql = "DELETE FROM delivery_person WHERE dpid='$dpid'";
    $result = $conn->query($sql);
    if ($result) {
        header("Location: deliveryperosn_list.php");
        exit; // Redirect after deletion
    } else {
        die("Error: " . $conn->error);
    }
}

// Fetch all delivery persons from the database
$query = "SELECT dpid, fullname, address, mobile, email, license, vehicle, vehicle_number, status FROM Delivery_Person";
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
    <title>Delivery Person List</title>
</head>
<body>
<link rel="stylesheet" href="../css/admin_table.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<div class="main-content">
    <h2 class="text-center" style="padding:10px">Delivery Person List</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-success'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']);
    }
    ?>
    <table class="table">
        <thead>
            <tr>
                <th>DP ID</th>
                <th>Full Name</th>
                <th>Address</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>License Number</th>
                <th>Vehicle</th>
                <th>Vehicle Number</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0) { 
                while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['dpid']); ?></td>
                    <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['mobile']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['license']); ?></td>
                    <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
                    <td><?php echo htmlspecialchars($row['vehicle_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <!-- Approve or Reject Delivery Person -->
                        <form method="post" action="">
                            <input type="hidden" name="dpid" value="<?php echo $row['dpid']; ?>">
                            <select name="status" class="form-select" onchange="this.form.submit()"
                                    <?php if ($row['status'] == 'Approved' || $row['status'] == 'Not Approved') echo 'disabled'; ?>>
                                <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Approved" <?php if ($row['status'] == 'Approved') echo 'selected'; ?>>Approved</option>
                                <option value="Not Approved" <?php if ($row['status'] == 'Not Approved') echo 'selected'; ?>>Not Approved</option>
                            </select>
                        </form>
                        <!-- Approve or Reject Delivery Person -->
                        <form method="post" action=" " style="display:inline;">
                                        <input type="hidden" value="<?php echo $row['dpid']; ?>" name="dpid" />
                                        <input type="submit" class="btn-danger" value="Remove" name="delete"
                                        onclick="return confirm('Are you sure you want to delete this category?');"
                                        style="background-color: red; color: white; border: none; cursor: pointer;" 
                                        onmouseover="this.style.backgroundColor='darkred';" 
                                        onmouseout="this.style.backgroundColor='red';" />
                                    </form>
                    </td>

                </tr>
            <?php } } else { ?>
                <tr>
                    <td colspan="10" class="text-center">No delivery persons found.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
// include('../admin/layout/adminfooter.php');
?>
