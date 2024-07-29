<?php
// Database connection parameters
$servername = "bo9qhkxjlzdhi2gazaym-mysql.services.clever-cloud.com";
$username = "unhzipmxu8pjeyww";
$password = "6loJLdNYcVsjjPJr7caP";
$dbname = "bo9qhkxjlzdhi2gazaym";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$userID = $_SESSION['userID']; // Assuming userID is the email

// Fetch profile data
$sql_fetch_profile = "SELECT * FROM profile WHERE userID='$userID'";
$result = $conn->query($sql_fetch_profile);

if ($result->num_rows > 0) {
    $profile_data = $result->fetch_assoc();

    // Return profile data as JSON response
    header('Content-Type: application/json');
    echo json_encode($profile_data);
} else {
    echo "No profile found";
}

$conn->close();
?>
