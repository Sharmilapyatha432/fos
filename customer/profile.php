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
include('../customer/layout/layout.php');

$cid = isset($_SESSION['cid']) ? (int)$_SESSION['cid'] : 0;
$sql = "SELECT * FROM customer WHERE cid = " . $cid;
$result = $conn->query($sql);

$row = null;
if ($result && $result->num_rows > 0) {
    // Fetch values in row
    $row = $result->fetch_assoc();
} else {
    header("Location: customer_panel.php");
    exit();
}
?>

<style>
    html, body { background: var(--softGreenColor) !important; }
    .main { background: var(--softGreenColor) !important; min-height: 100vh; }
</style>
<link rel="stylesheet" href="../css/view_profile.css">
<div class="content-area">
<div class="profile-page">
    <div class="profile-card">
        <div class="profile-hero">
            <div class="avatar">
                <i class="fa-solid fa-user"></i>
            </div>
            <div class="hero-text">
                <p class="eyebrow">Customer</p>
                <h1><?php echo htmlspecialchars($row['name'] ?? ''); ?></h1>
                <p class="status">Email: <strong><?php echo htmlspecialchars($row['email'] ?? ''); ?></strong></p>
            </div>
        </div>

        <div class="profile-grid">
            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-body">
                    <p class="info-label">Address</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['address'] ?? ''); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                <div class="info-body">
                    <p class="info-label">Mobile</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['mobile'] ?? ''); ?></p>
                </div>
            </div>

            <div class="info-tile">
                <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="info-body">
                    <p class="info-label">Email</p>
                    <p class="info-value"><?php echo htmlspecialchars($row['email'] ?? ''); ?></p>
</div>
</div>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">

        <div class="profile-actions">
            <button onclick="window.location.href='customer_panel.php';" class="btn-ghost">
                Back to Dashboard
            </button>
            <button onclick="window.location.href='edit_profile.php';" class="btn-primary">
                Edit Profile
            </button>
        </div>
    </div>
</div>
</div>
