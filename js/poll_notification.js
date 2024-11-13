setInterval(function() {
    fetch('/check_notifications.php')
    .then(response => response.json())
    .then(data => {
        if (data.new_notifications) {
            alert(data.message); // Display notification message
            // Mark the notification as read
            fetch(`/mark_as_read.php?id=${data.notification_id}`);
        }
    });
}, 5000); // Check every 5 seconds
