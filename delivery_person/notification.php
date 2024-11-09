<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Push Notification</title>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging.js"></script>
</head>
<body>
    <h1>Delivery Notifications</h1>

    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "YOUR_API_KEY",
            authDomain: "your-app.firebaseapp.com",
            projectId: "your-app",
            storageBucket: "your-app.appspot.com",
            messagingSenderId: "your-messaging-sender-id",
            appId: "your-app-id",
            measurementId: "your-measurement-id"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        // Request permission for notifications
        messaging.requestPermission()
        .then(function() {
            console.log('Notification permission granted.');
            // Get the registration token
            return messaging.getToken();
        })
        .then(function(token) {
            console.log("FCM Token:", token);
            // Send this token to the server to subscribe this user to notifications
        })
        .catch(function(err) {
            console.error('Error getting permission for notifications', err);
        });

        // Handle incoming messages
        messaging.onMessage(function(payload) {
            console.log('Message received:', payload);
            // Display notification here
        });
    </script>
</body>
</html>
