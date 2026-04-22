<?php
$servername = "127.0.0.1";   // IMPORTANT
$username   = "root";
$password   = "";            // blank (as per phpMyAdmin)
$database   = "mytrip";
$port       = 3308;

$conn = mysqli_connect($servername, $username, $password, $database, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Twilio SMS Configuration
// Sign up at twilio.com to get these credentials
define('TWILIO_SID', 'YOUR_TWILIO_SID');
define('TWILIO_TOKEN', 'YOUR_TWILIO_AUTH_TOKEN');
define('TWILIO_NUMBER', 'YOUR_TWILIO_PHONE_NUMBER');

function sendSMS($to, $message) {
    $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_SID . "/Messages.json";
    $auth = TWILIO_SID . ":" . TWILIO_TOKEN;

    $data = array(
        'From' => TWILIO_NUMBER,
        'To' => $to,
        'Body' => $message
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $auth);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    return $response;
}
?>
