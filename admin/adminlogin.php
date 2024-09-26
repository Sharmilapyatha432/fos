<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include('../database/connection.php');
session_start();
if(isset($_POST['login']))
{
    $adminname = $_POST['adminname'];
    $adminpassword = $_POST['adminpassword'];
    
    $sql = "SELECT * FROM admin WHERE adminname = '$adminname' and adminpassword = '$adminpassword'";

    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $_SESSION['adminname'] = $adminname;

        // For logout Purpose
        $_SESSION['admin'] =  $_POST['adminname'];
        // Redirect to the admin panel page
        header ("Location: adminpanel.php");
        exit();
    }else{
        // Redirect to the same login page with an error message
        header("Location: adminlogin.php?error=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<link rel="stylesheet" href="../css/adminlogin.css">
<body>
    <div class="container">
    <div class="login-form">
    <h2>ADMIN LOGIN PANEL</h2>
    <form action="" method="post" autocomplete="off">
        <div class="input-field">
            <input type="text" placeholder="Admin Name" name="adminname">
        </div>
        <div class="input-field">
            <input type="password" placeholder="Password" name="adminpassword">
        </div>
        <button type="submit" name="login">Log in</button>

    </form>
    </div>
    </div>
</body>
</html>