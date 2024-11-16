<?php
include('../database/connection.php');

$result = $conn->query("SELECT id, message FROM notifications WHERE is_read = FALSE ORDER BY created_at DESC LIMIT 1");

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'new_notifications' => true,
        'message' => $row['message'],
        'notification_id' => $row['id']
    ]);
} else {
    echo json_encode(['new_notifications' => false]);
}

$conn->close();


?>

<form action="">
    
</form>
