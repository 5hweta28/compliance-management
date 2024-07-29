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

$sql = "SELECT doc_num, doctype, filename, supplier_name, purchase_date, due_date, total_amount FROM doc WHERE doc_num = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $doc_num);
$stmt->execute();
$result = $stmt->get_result();

$classification_result = array();

if ($result->num_rows > 0) {
    $classification_result = $result->fetch_assoc();
}

header('Content-Type: application/json');
echo json_encode($classification_result);

$stmt->close();
$conn->close();
?>
