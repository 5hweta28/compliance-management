<?php
session_start();

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

// Get logged-in user's email from session
$assignedCA = $_SESSION['userID'];

// Fetch tasks assigned to the logged-in user
$sql = "SELECT doc_name, doc_num, type, status, userID, completion_status FROM task WHERE assignedCA = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $assignedCA);
$stmt->execute();
$result = $stmt->get_result();

$tasks = array();
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}

// Output data as JSON
header('Content-Type: application/json');
echo json_encode($tasks);

$stmt->close();
$conn->close();
?>