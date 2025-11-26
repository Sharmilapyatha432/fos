<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$errors = [];

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('../database/connection.php');
include('../customer/layout/layout.php');

$cid = isset($_SESSION['cid']) ? (int)$_SESSION['cid'] : 0;
$stmt = $conn->prepare("SELECT * FROM customer WHERE cid = ?");
$stmt->bind_param("i", $cid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if (!$row) {
    header("Location: customer_panel.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $newPassword = $_POST['password'] ?? '';

    if ($name === '' || $address === '' || $mobile === '' || $email === '') {
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
            $update = $conn->prepare("UPDATE customer SET name = ?, address = ?, mobile = ?, email = ?, password = ? WHERE cid = ?");
            $update->bind_param("sssssi", $name, $address, $mobile, $email, $hashedPassword, $cid);
        } else {
            $update = $conn->prepare("UPDATE customer SET name = ?, address = ?, mobile = ?, email = ? WHERE cid = ?");
            $update->bind_param("ssssi", $name, $address, $mobile, $email, $cid);
        }

        if ($update && $update->execute()) {
            header("Location: profile.php?success=1");
            exit;
        } else {
            header("Location: profile.php?error=db_update_failed");
            exit;
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

<link rel="stylesheet" type="text/css" href="../css/form.css">
<style>
    html, body { background: var(--softGreenColor) !important; }
    .main { background: var(--softGreenColor) !important; min-height: 100vh; }
    .form-card h2 { text-align: center; }
</style>

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
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? ''); ?>">
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

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
