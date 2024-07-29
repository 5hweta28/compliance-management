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
$userID = $_SESSION['userID'];

// Fetch counts from task table
$sql_assigned_doc_count = "SELECT COUNT(*) AS assigned_doc_count FROM task WHERE userID='$userID'";
$sql_pending_task_count = "SELECT COUNT(*) AS pending_task_count FROM task WHERE userID='$userID' AND status='pending'";
$sql_completed_task_count = "SELECT COUNT(*) AS completed_task_count FROM task WHERE userID='$userID' AND completion_status='completed'";

$result_assigned_doc = $conn->query($sql_assigned_doc_count);
$result_pending_task = $conn->query($sql_pending_task_count);
$result_completed_task = $conn->query($sql_completed_task_count);

if ($result_assigned_doc && $result_pending_task && $result_completed_task) {
    $row_assigned_doc = $result_assigned_doc->fetch_assoc();
    $row_pending_task = $result_pending_task->fetch_assoc();
    $row_completed_task = $result_completed_task->fetch_assoc();

    // Assign counts to variables
    $assigned_doc_count = $row_assigned_doc['assigned_doc_count'];
    $pending_task_count = $row_pending_task['pending_task_count'];
    $completed_task_count = $row_completed_task['completed_task_count'];
} else {
    $assigned_doc_count = 0;
    $pending_task_count = 0;
    $completed_task_count = 0;
}

// Prepare response as JSON
$response = array(
    'assigned_doc_count' => $assigned_doc_count,
    'pending_task_count' => $pending_task_count,
    'completed_task_count' => $completed_task_count
);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
