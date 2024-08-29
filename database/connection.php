<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "bite_bliss";

//Creating connection with database
$conn = new mysqli($server, $username, $password, $database);

//Check connection
if($conn -> connect_error){
    die("Connection Error:" .$conn->connect_error);
    exit;
}

?>