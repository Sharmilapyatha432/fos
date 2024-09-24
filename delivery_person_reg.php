<?php
include('database/connection.php');

// Define table name
$dperson = "delivery_person";

// Submission process
if (isset($_POST['signup'])) {
    // Get data
    $fullname = $_POST['fullname'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $email = strtolower($_POST['email']);
    $password = $_POST['password'];
    $licenseno = $_POST['licenceno'];
    $vehicle = $_POST['vehicle'];
    $vehicleno = $_POST['vehicleno'];

    // Validations
    $errors = [];

    // Check if all fields are filled
    if (empty($fullname) || empty($email) || empty($address) || empty($mobile) || empty($password)
        || empty($licenseno) || empty($vehicle) || empty($vehicleno)) {
        $errors[] = "All fields are required.";
    }

    // Password validation
    if (strlen($password) < 8 || strlen($password) > 16) {
        $errors[] = "Password must be between 8 and 16 characters.";
    }

    // Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Name validation
    if (!preg_match("/^[a-zA-Z ]*$/", $fullname)) {
        $errors[] = "Full name should only contain letters and spaces.";
    }

    // Mobile number validation
    if (strlen($mobile) !== 10 || !is_numeric($mobile)) {
        $errors[] = "Mobile number should be numeric and 10 digits long.";
    }

    // License number validation (alphanumeric, 6-12 characters)
    if (!preg_match("/^[A-Za-z0-9]{6,12}$/", $licenseno)) {
        $errors[] = "Invalid license number format. Must be 6-12 alphanumeric characters.";
    }

    // Vehicle type validation
    $valid_vehicles = ['Motorcycle', 'Scooter'];
    if (!in_array($vehicle, $valid_vehicles)) {
        $errors[] = "Invalid vehicle selection.";
    }

    // Unique Email Validation
    $sql_check_mail = "SELECT * FROM $dperson WHERE email = ?";
    $stmt_check_mail = $conn->prepare($sql_check_mail);
    $stmt_check_mail->bind_param("s", $email);
    $stmt_check_mail->execute();
    $result_check_mail = $stmt_check_mail->get_result();
    if ($result_check_mail->num_rows > 0) {
        $errors[] = "Email already registered.";
    }

    // If there are no errors, proceed with inserting into the database
    if (empty($errors)) {
        // Prepare the SQL query
        $sql = "INSERT INTO $dperson (fullname, address, mobile, email, password, license, vehicle, vehicle_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $errors[] = "Error in database connection.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Bind parameters and execute the statement
            $stmt->bind_param("ssssssss", $fullname, $address, $mobile, $email, $hashedPassword, $licenseno, $vehicle, $vehicleno);
            if ($stmt->execute()) {
                // Success message and redirection
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Swal.fire({
                            icon: "success",
                            title: "Registration Successful",
                            text: "Registration Successful",
                            showCloseButton: true,
                        }).then(function() {
                            window.location.href = "login.php?success=1";
                        });
                    });
                </script>';
            } else {
                $errors[] = "An error occurred while processing your request. Please try again later.";
            }
        }
    }

    // Display errors using SweetAlert
    if (!empty($errors)) {
        $errorMessages = join("<br>", $errors); // Use <br> for line breaks in HTML
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "Sign Up Errors",
                    html: "' . $errorMessages . '",
                    showCloseButton: true,
                });
            });
        </script>';
    }
}
?>

<!-- HTML Code Starts From Here -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delivery Person Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="css/dp_reg.css">
</head>
<body>
    <!-- Form Layout -->
    <div class="container">
        <h3>Registration as Delivery Person</h3>
        <form class="row g-3" action="delivery_person_reg.php" method="post">
            <div class="col-md-6">
                <label for="fullname" class="form-label">Full Name:</label>
                <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter your full name" required>
            </div>
            <div class="col-md-6">
                <label for="address" class="form-label">Address:</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" required>
            </div>
            <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile no:</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
            </div>
            <div class="col-md-6">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Password:</label>
                <input type="password" class="form-control" id="inputPassword4" name="password" placeholder="Enter your password" required>
            </div>
            <div class="col-md-6">
                <label for="licenceno" class="form-label">License Number:</label>
                <input type="text" class="form-control" id="licenceno" name="licenceno" placeholder="Enter your license number" required>
            </div>
            <div class="col-md-6">
                <label for="inputState" class="form-label">Vehicles:</label>
                <select id="inputState" class="form-select" name="vehicle" required>
                    <option selected disabled>Choose Vehicle</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Scooter">Scooter</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="vehicleno" class="form-label">Vehicle no:</label>
                <input type="text" class="form-control" id="vehicleno" name="vehicleno" placeholder="Enter your vehicle number" required>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-success" name="signup">SIGNUP</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- Include SweetAlert2 -->
</body>
</html>
