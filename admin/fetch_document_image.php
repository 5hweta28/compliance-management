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

$doc_num = $_GET['doc_num'];

$sql = "SELECT doc_image FROM doc WHERE doc_num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doc_num);
$stmt->execute();
$result = $stmt->get_result();

$document_image = array();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $document_image['doc_image'] = base64_encode($row['doc_image']);
}

header('Content-Type: application/json');
echo json_encode($document_image);

$stmt->close();
$conn->close();
?>
