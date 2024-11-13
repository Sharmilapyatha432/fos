<?php
$notificationId = $_GET['id'];
include('../database/connection.php');

$stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
$stmt->bind_param("i", $notificationId);
$stmt->execute();
$stmt->close();
$conn->close();
?>
