<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$errors = []; // Array to store validation errors

// Check if session variables 'email' and 'dpid' are set
if (!isset($_SESSION['email']) || !isset($_SESSION['dpid'])) {
    echo "Session variables 'email' or 'dpid' are not set.";
    header("Location: login.php"); // Redirect to Login Page if not logged in
    exit();
}

$email = $_SESSION['email']; // Fetch logged-in user email
$dpid = $_SESSION['dpid']; // Fetch logged-in user dpid

// Database Connection
include('../database/connection.php');
include('../customer/layout/header.php');

// Fetch delivery person details from the database
$sql = "SELECT * FROM delivery_person WHERE dpid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $dpid); // Binding the 'dpid' parameter
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Check if the user exists
if (!$row) {
    echo "No user found with dpid: $dpid";
    header("Location: login.php"); // If no user is found, redirect to login
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather form data
    $name = trim($_POST['fullname']);
    $address = trim($_POST['address']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $newPassword = $_POST['password'];
    $licenseno = $_POST['licenceno'];
    $vehicle = $_POST['vehicle'];
    $vehicleno = $_POST['vehicleno'];

    // Validation
    if (empty($name) || empty($email) || empty($mobile) || empty($address) || empty($licenseno) || empty($vehicle) || empty($vehicleno)) {
        $errors[] = "All fields are required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (!empty($newPassword) && (strlen($newPassword) < 8 || strlen($newPassword) > 16)) {
        $errors[] = "Password must be between 8 and 16 characters.";
    }

    if (strlen($mobile) !== 10 || !is_numeric($mobile)) {
        $errors[] = "Mobile number should be 10 digits only.";
    }

    if (empty($errors)) {
        // Prepare the SQL query for updating the profile
        $updateSql = "UPDATE delivery_person SET 
                        fullname = ?, 
                        address = ?, 
                        mobile = ?, 
                        email = ?, 
                        license = ?, 
                        vehicle = ?, 
                        vehicle_number = ?";

        // If a new password is provided, add the password update
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql .= ", password = ?";
        }

        $updateSql .= " WHERE dpid = ?";

        // Prepare and bind the parameters
        $stmt = $conn->prepare($updateSql);

        if (!empty($newPassword)) {
            $stmt->bind_param("ssssssssi", $name, $address, $mobile, $email, $licenseno, $vehicle, $vehicleno, $hashedPassword, $dpid);
        } else {
            $stmt->bind_param("sssssssi", $name, $address, $mobile, $email, $licenseno, $vehicle, $vehicleno, $dpid);
        }

        // Execute the query
        if ($stmt->execute()) {
            // Update session variables with new values
            $_SESSION['name'] = $name;
            $_SESSION['address'] = $address;
            $_SESSION['mobile'] = $mobile;
            $_SESSION['email'] = $email;
            $_SESSION['licenseno'] = $licenseno;
            $_SESSION['vehicle'] = $vehicle;
            $_SESSION['vehicleno'] = $vehicleno;

            // Redirect to the profile page with a success message
            header("Location: profile.php?success=1");
            exit();
        } else {
            // If update fails, redirect to the profile page with an error message
            header("Location: profile.php?error=db_update_failed");
            exit();
        }
    } else {
        // Display errors using SweetAlert
        $errorMessages = join("\n", $errors);
        echo '<script>
        Swal.fire({
            icon: "error",
            title: "Validation Errors",
            html: "' . $errorMessages . '",
            showCloseButton: true,
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
    <title>My Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function (event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });

        // Client-side validation function with SweetAlert integration
        function validateForm() {
            var errors = [];
            var name = document.getElementById("fullname").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var mobile = document.getElementById("mobile").value;

            if (name === "") {
                errors.push("Name is required.");
            }

            if (email === "") {
                errors.push("Email is required.");
            } else if (!validateEmail(email)) {
                errors.push("Invalid email format.");
            }

            if (password !== "" && (password.length < 8 || password.length > 16)) {
                errors.push("Password must be between 8 and 16 characters.");
            }

            if (mobile === "") {
                errors.push("Mobile number is required.");
            } else if (mobile.length !== 10) {
                errors.push("Mobile number must be 10 digits.");
            }

            // Display errors using SweetAlert
            if (errors.length > 0) {
                var errorMessage = `<div class="error-list">${errors.map(error => `• ${error}`).join("<br>")}</div>`;
                Swal.fire({
                    icon: 'error',
                    title: 'Update Error',
                    html: errorMessage,
                    showCloseButton: true,
                });

                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }

        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            return re.test(email);
        }

        function enableEdit(field) {
            document.getElementById(field).readOnly = false;
        }
    </script>
</head>
<body>
<div class="container">
    <h1>My Profile</h1>

    <!-- Display success or error messages -->
    <?php if (isset($_GET['success'])) { ?>
        <div class="success-message">
            Profile update successful!
        </div>
    <?php } elseif (isset($_GET['error'])) { ?>
        <div class="error-message">
            Failed to update profile. Please try again.
        </div>
    <?php } ?>

    <form action="" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">

        <!-- Form fields for profile details -->
        <div class="form-group">
            <i class="fa-solid fa-circle-user"></i>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($row['fullname']); ?>" readonly>
            <button type="button" class="edit-button" onclick="enableEdit('fullname')">Edit</button>
        </div>

        <!-- Other fields follow the same pattern -->

        <div class="form-group">
            <input type="submit" value="Save Changes">
        </div>

    </form>

    <a href="../dashboard.php">Back</a>
</div>
</body>
</html>
