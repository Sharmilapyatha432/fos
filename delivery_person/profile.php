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

// Database Connection
include('../database/connection.php');
include('../delivery_person/layout/dheader.php');

// Query to fetch delivery person details
$sql = "SELECT * FROM delivery_person WHERE dpid = '" . $_SESSION['dpid'] . "'";
$result = $conn->query($sql);

$row = null;
if ($result && $result->num_rows > 0) {
    // Fetch values in row
    $row = $result->fetch_assoc();
}

?>

<link rel="stylesheet" href="../css/view_profile.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

<div class="profile-page">
    <div class="profile-card">
        <?php if ($row): ?>
        <div class="profile-hero">
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="hero-text">
                <p class="eyebrow">Delivery Partner</p>
                <h1><?php echo htmlspecialchars($row['fullname'] ?? 'N/A'); ?></h1>
                <p class="status">Status: <strong><?php echo htmlspecialchars($row['status'] ?? 'N/A'); ?></strong></p>
            </div>
        </div>

        <div class="profile-grid">
            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-body">
                    <p class="info-label">Address</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['address'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <div class="info-body">
                    <p class="info-label">Mobile</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['mobile'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="info-body">
                    <p class="info-label">Email</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-id-card"></i></div>
                <div class="info-body">
                    <p class="info-label">License No.</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['license'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-motorcycle"></i></div>
                <div class="info-body">
                    <p class="info-label">Vehicle</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['vehicle'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-credit-card"></i></div>
                <div class="info-body">
                    <p class="info-label">Vehicle Number</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['vehicle_number'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <button class="btn-ghost" onclick="window.location.href='dp_dashboard.php';">Back to Dashboard</button>
            <button class="btn-primary" onclick="window.location.href='edit_profile.php';">Edit Profile</button>
        </div>
        <?php else: ?>
            <div class="profile-hero">
                <div class="hero-text">
                    <h1>No profile data found</h1>
                    <p class="status">Please contact the admin.</p>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn-ghost" onclick="window.location.href='dp_dashboard.php';">Back to Dashboard</button>
            </div>
        <?php endif; ?>
    </div>
</div>
