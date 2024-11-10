<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$errors = []; // Array to store validation errors

// Checking if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirecting to Login Page
    exit();
}
$email = $_SESSION['email']; // Fetch logged-in user email
// $dpid = $_SESSION['dpid']; // Fetch logged-in user email

// Database Connection
include('../database/connection.php');
include('../customer/layout/header.php');
// include('../customer/layout/sidebar.php');

$sql = "SELECT * FROM delivery_person WHERE dpid = '" . $_SESSION['dpid'] . "'";
$result = $conn->query($sql);

$row = null;
if ($result && $result->num_rows > 0) {
    // Fetch values in row
    $row = $result->fetch_assoc();
}
?>

<link rel="stylesheet" href="../css/view_profile.css">
<div class="box-container">
    <div class="box">
        <h1>My Profile</h1>

        <div class="form-group">
            <i class="fa-solid fa-circle-user"></i>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>" readonly>
        </div>

        <div class="form-group">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" readonly>
        </div>

        <div class="form-group">
        <i class="fa-solid fa-mobile-screen-button"></i>
            <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($row['mobile']); ?>" readonly>
        </div>

        <div class="form-group">
        <i class="fa-solid fa-envelope"></i>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" readonly>
        </div>

        <div class="form-group">
            <i class="fa-solid fa-id-card"></i>
            <input type="text" id="licenseno" name="licenseno" placeholder="Enter license number" value="<?php echo htmlspecialchars($row['license']); ?>" readonly >
            <!-- <button type="button" class="edit-button" onclick="enableEdit('licenseno')">Edit</button> -->
        </div>

        <div class="form-group">
            <i class="fa-solid fa-car"></i>
            <select id="inputState" class="form-select" name="vehicle" value="<?php echo htmlspecialchars($row['vehicle']); ?>" readonly>
                    <option selected disabled>Choose Vehicle</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Scooter">Scooter</option>
                </select>
            <!-- <button type="button" class="edit-button" onclick="enableEdit('vehicle')">Edit</button> -->
        </div>

        <div class="form-group">
        <i class="fa-solid fa-credit-card"></i>
            <input type="text" id="vehicleno" name="vehicleno" placeholder="Enter new vehicleno" value="<?php echo htmlspecialchars($row['vehicle_number']); ?>" readonly>
            <!-- <button type="button" class="edit-button" onclick="enableEdit('vehicleno')">Edit</button> -->
        </div>

        <button onclick="window.location.href='dp_dashboard.php';" 
        style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none;
        border-radius: 5px; cursor: pointer; text-decoration: none; margin-right: 10px;"
        onmouseover="this.style.backgroundColor='#45a049';" onmouseout="this.style.backgroundColor='#4CAF50';">
        Back
        </button>
        <button onclick="window.location.href='edit_profile.php';" 
        style="padding: 10px 20px; background-color: #008CBA; color: white; border: none;
        border-radius: 5px; cursor: pointer;" onmouseover="this.style.backgroundColor='#007B8F';"
        onmouseout="this.style.backgroundColor='#008CBA';">
        Edit Profile
        </button>
    </div>
</div>
