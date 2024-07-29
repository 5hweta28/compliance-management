<?php
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

// Get POST data
$postData = json_decode(file_get_contents("php://input"));

if (isset($postData->doc_name) && isset($postData->assignedCA)) {
    $docName = $conn->real_escape_string($postData->doc_name);
    $assignedCA = $conn->real_escape_string($postData->assignedCA);

    // Update task with new Assign CA
    $sql = "UPDATE task SET assignedCA='$assignedCA' WHERE doc_name='$docName'";

    if ($conn->query($sql) === TRUE) {
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        $response = array('success' => false, 'error' => $conn->error);
        echo json_encode($response);
    }
} else {
    $response = array('success' => false, 'error' => 'Invalid input data');
    echo json_encode($response);
}

$conn->close();
?>
