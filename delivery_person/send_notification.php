<?php
// Your Firebase server key
$firebaseServerKey = 'YOUR_FIREBASE_SERVER_KEY';

// FCM Token of the delivery person (retrieved from your database)
$deliveryPersonToken = 'DELIVERY_PERSON_FCM_TOKEN';

// The notification content
$notification = [
    'title' => 'New Order Received!',
    'body' => 'You have a new order to deliver. Please check the app.',
    'icon' => 'your-icon-url', // URL to an icon image
    'click_action' => 'https://yourapp.com/orders' // URL to redirect when notification is clicked
];

// Data payload (optional, can include additional data like order details)
$data = [
    'customer_name' => 'John Doe',
    'delivery_address' => '123 Street, Kathmandu',
    'estimated_time' => '20 minutes'
];

// Prepare the fields to send in the FCM request
$fields = [
    'to' => $deliveryPersonToken, // Send to specific token
    'notification' => $notification,
    'data' => $data
];

// Prepare headers
$headers = [
    'Authorization: key=' . $firebaseServerKey,
    'Content-Type: application/json'
];

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute the cURL request
$response = curl_exec($ch);

// Check if the request was successful
if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
}

// Close the cURL session
curl_close($ch);

// Print the FCM response
echo $response;
?>
