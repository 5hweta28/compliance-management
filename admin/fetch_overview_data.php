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

$sql = "SELECT doc_name, doc_num, type, status FROM task";
$result = $conn->query($sql);

$overview_data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $overview_data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($overview_data);

$conn->close();
?>
