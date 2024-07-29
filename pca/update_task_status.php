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

$doc_num = $_POST['doc_num'];
$completion_status = $_POST['completion_status'];

// Update completion status
$sql = "UPDATE task SET completion_status = ? WHERE doc_num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $completion_status, $doc_num);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "success";
} else {
    echo "error";
}

$stmt->close();
$conn->close();
?>