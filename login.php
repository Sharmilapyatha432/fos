<?php
// include('layout/header.php');
//include('layout/left.php');
// ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('database/connection.php');             //Database Connection Included

session_start();
// Check if the user is already logged in
if (isset($_SESSION['email'])) {
    header("location: ");
    exit;
}

if (isset($_POST['login'])) {
    // Get user username or password from the form
    $email = strtolower($_POST['email']);
    $password = $_POST['password'];
    $userselects = $_POST['userselects'];

    // Use prepared statements to prevent SQL injection
    $sql = "";
    if ($userselects == "customer") {
        $sql = "SELECT * FROM customer WHERE email = ?";
    } elseif ($userselects == "delivery person") {
        $sql = "SELECT * FROM deliveryperson WHERE email = ?";
    }

    // Prepares the SQL statement, binds the email parameter, executes the statement, and retrieves the result.
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // If there is at least one row in the result, fetches the associative array representing the user data and retrieves the hashed password from the database.
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedpassword = $row['password'];

        if (password_verify($password, $hashedpassword)) {
            // Check if the counsellor is approved
            if ($userselects == "delivery person" && $row['status'] !== 'Approved') {
                // Display message and prevent login
                // echo "Wait for admin approval. You cannot log in at the moment.";
                echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "Wait",
                        title: "Please Wait!",
                        text: "Wait for admin approval. You cannot log in at the moment.",
                        showCloseButton: true,
                    });
                });
            </script>';
            } else {

                // Password matches, store user details in session
                $_SESSION['id'] = $row['id'];
                $_SESSION['email'] = $email;
                $_SESSION['usertype'] = $userselects;

                if ($userselects == "customer") {
                    header("location: #");
                } elseif ($userselects == "delivery person") {
                    header("location: #");
                }
                exit;
            }
        } else {
            header("Location: login.php?error=1");  //If the password verification fails, redirects to the login page with an error parameter.
            exit;
        }
    } else {
        header("Location: login.php?error=1");      //If no user is found with the provided email, redirects to the login page with an error parameter
        exit;
    }
}
?>


<!--------- HTML --------->

<link rel="stylesheet" href="css/login.css">

<div class="log">
    <div class="container">
        <h1>BiteBliss Login</h1>
        <?php if (isset($_GET['error'])) { ?>
        <div class="error-message">
            Email or Password Invalid!
        </div>
        <?php } ?>
        <form method="post" action="login.php" autocomplete="off">
            <div class="group-login">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="group-login">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password "required>
            </div>
            <div class="user-selects">
                <div class="customer-part">  
                    <input type="radio" name="userselects" id="customer" value="customer" checked>
                    <label for="customer">Customer</label>
                </div>
                <div class="deliveryperson-part">
                    <input type="radio" name="userselects" id="deliveryperson" value="deliveryperson">
                    <label for="deliveryperson">Delivery Person</label>
                </div> 
            </div>

            <span>Forgot Password? <a href="forgetpassword/forgetpwd.php">Click Here!</a></span>
            <div class="button-group">
                <button type="submit" name="login" value="login">LOGIN</button>
            </div>
            <span>Don't have an account? <a href="registration.php">Register Here!!!</a></span>
        </form>
    </div>
</div>

