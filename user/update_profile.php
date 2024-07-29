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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    // Update profile table
    $sql_update_profile = "UPDATE profile SET name='$name', phone='$phone', location='$location' WHERE userID='$userID'";

    if ($conn->query($sql_update_profile) === TRUE) {
        // Update login table if necessary (name change)
        $sql_update_login = "UPDATE login SET name='$name' WHERE userID='$userID'";
        $conn->query($sql_update_login);

        // Fetch updated profile data to send back to client
        $sql_fetch_profile = "SELECT * FROM profile WHERE userID='$userID'";
        $result = $conn->query($sql_fetch_profile);
        $profile_data = $result->fetch_assoc();

        // Return updated profile data as JSON response
        header('Content-Type: application/json');
        echo json_encode($profile_data);
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}

$conn->close();
?>
