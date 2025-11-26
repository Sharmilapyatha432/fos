<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$errors = [];

if (!isset($_SESSION['email']) || !isset($_SESSION['dpid'])) {
    header("Location: login.php");
    exit();
}

include('../database/connection.php');
include('../delivery_person/layout/dheader.php');

$dpid = (int)$_SESSION['dpid'];
$stmt = $conn->prepare("SELECT * FROM delivery_person WHERE dpid = ?");
$stmt->bind_param("i", $dpid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $licenseno = trim($_POST['licenceno'] ?? '');
    $vehicle = trim($_POST['vehicle'] ?? '');
    $vehicleno = trim($_POST['vehicleno'] ?? '');
    $newPassword = $_POST['password'] ?? '';

    if ($fullname === '' || $address === '' || $mobile === '' || $email === '' || $licenseno === '' || $vehicle === '' || $vehicleno === '') {
        $errors[] = "All fields are required.";
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if ($newPassword !== '' && (strlen($newPassword) < 8 || strlen($newPassword) > 16)) {
        $errors[] = "Password must be between 8 and 16 characters.";
    }
    if ($mobile !== '' && (strlen($mobile) !== 10 || !ctype_digit($mobile))) {
        $errors[] = "Mobile number must be 10 digits.";
    }

    if (empty($errors)) {
        if ($newPassword !== '') {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE delivery_person SET fullname = ?, address = ?, mobile = ?, email = ?, license = ?, vehicle = ?, vehicle_number = ?, password = ? WHERE dpid = ?");
            $update->bind_param("ssssssssi", $fullname, $address, $mobile, $email, $licenseno, $vehicle, $vehicleno, $hashedPassword, $dpid);
        } else {
            $update = $conn->prepare("UPDATE delivery_person SET fullname = ?, address = ?, mobile = ?, email = ?, license = ?, vehicle = ?, vehicle_number = ? WHERE dpid = ?");
            $update->bind_param("sssssssi", $fullname, $address, $mobile, $email, $licenseno, $vehicle, $vehicleno, $dpid);
        }

        if ($update && $update->execute()) {
            header("Location: profile.php?success=1");
            exit();
        } else {
            header("Location: profile.php?error=db_update_failed");
            exit();
        }
    } else {
        $errorMessages = join("<br>", array_map('htmlspecialchars', $errors));
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo '<script>
            Swal.fire({
                icon: "error",
                title: "Update Error",
                html: "' . $errorMessages . '",
                showCloseButton: true
            });
        </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/form.css">
    <style>
        html, body { background: var(--softGreenColor) !important; }
        .main { background: var(--softGreenColor) !important; min-height: 100vh; }
    </style>
</head>
<body>
<div class="content-area">
    <div class="form-shell">
        <div class="form-card">
            <h2>Edit Profile</h2>
            <?php if (isset($_GET['success'])) { ?>
                <div class="success-message">Update successful!</div>
            <?php } elseif (isset($_GET['error'])) { ?>
                <div class="error-message">Update failed!</div>
            <?php } ?>
            <form action="" method="POST" class="form-grid">
                <div class="form-field">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($row['fullname'] ?? ''); ?>">
                </div>
                <div class="form-field">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($row['address'] ?? ''); ?>">
                </div>
                <div class="form-field">
                    <label for="mobile">Mobile</label>
                    <input type="text" id="mobile" name="mobile" value="<?php echo htmlspecialchars($row['mobile'] ?? ''); ?>">
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? ''); ?>">
                </div>
                <div class="form-field">
                    <label for="licenceno">License Number</label>
                    <input type="text" id="licenceno" name="licenceno" value="<?php echo htmlspecialchars($row['license'] ?? ''); ?>">
                </div>
                <div class="form-field">
                    <label for="vehicle">Vehicle</label>
                    <select id="vehicle" name="vehicle">
                        <option value="" disabled <?php echo empty($row['vehicle']) ? 'selected' : ''; ?>>Select vehicle</option>
                        <option value="Motorcycle" <?php echo ($row['vehicle'] ?? '') === 'Motorcycle' ? 'selected' : ''; ?>>Motorcycle</option>
                        <option value="Scooter" <?php echo ($row['vehicle'] ?? '') === 'Scooter' ? 'selected' : ''; ?>>Scooter</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="vehicleno">Vehicle Number</label>
                    <input type="text" id="vehicleno" name="vehicleno" value="<?php echo htmlspecialchars($row['vehicle_number'] ?? ''); ?>">
                </div>
                <div class="form-field">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter new password">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-ghost" onclick="window.location.href='profile.php'">Back</button>
                    <button type="submit" class="btn-primary-solid">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
