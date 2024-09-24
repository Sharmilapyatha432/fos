<?php 
//Start session
session_start();

//Clear all the session variables
$_SESSION=array();

//Destroy Session
session_destroy();

//Redirect to the login page or any other page
header("Location: login.php");
exit();
?>