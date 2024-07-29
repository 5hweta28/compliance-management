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

// Fetch counts from database
$sql_total_users = "SELECT COUNT(*) AS total_users FROM login";
$sql_total_documents = "SELECT COUNT(*) AS total_documents FROM doc";
$sql_total_verified = "SELECT COUNT(*) AS total_verified FROM task WHERE status = 'Verified'";
$sql_total_pending = "SELECT COUNT(*) AS total_pending FROM task WHERE status = 'Pending'";

$result_total_users = $conn->query($sql_total_users);
$result_total_documents = $conn->query($sql_total_documents);
$result_total_verified = $conn->query($sql_total_verified);
$result_total_pending = $conn->query($sql_total_pending);

// Initialize variables
$total_users = 0;
$total_documents = 0;
$total_verified = 0;
$total_pending = 0;

// Process results
if ($result_total_users && $result_total_users->num_rows > 0) {
    $row = $result_total_users->fetch_assoc();
    $total_users = $row['total_users'];
}

if ($result_total_documents && $result_total_documents->num_rows > 0) {
    $row = $result_total_documents->fetch_assoc();
    $total_documents = $row['total_documents'];
}

if ($result_total_verified && $result_total_verified->num_rows > 0) {
    $row = $result_total_verified->fetch_assoc();
    $total_verified = $row['total_verified'];
}

if ($result_total_pending && $result_total_pending->num_rows > 0) {
    $row = $result_total_pending->fetch_assoc();
    $total_pending = $row['total_pending'];
}

// Fetch recent activity from task table
$sql_recent_activity = "SELECT t.doc_name, d.doctype AS type, d.supplier_name AS client, t.date, t.status, t.assignedCA
                       FROM task t
                       INNER JOIN doc d ON t.doc_num = d.doc_num
                       ORDER BY t.date DESC
                       LIMIT 5";

$result_recent_activity = $conn->query($sql_recent_activity);

// Initialize array for recent activity data
$recent_activity = array();

// Process recent activity results
if ($result_recent_activity && $result_recent_activity->num_rows > 0) {
    while ($row = $result_recent_activity->fetch_assoc()) {
        $recent_activity[] = array(
            'doc_name' => $row['doc_name'],
            'type' => $row['type'],
            'client' => $row['client'],
            'date' => $row['date'],
            'status' => $row['status'],
            'assigned_ca' => $row['assignedCA']
        );
    }
}

// Prepare response array
$response = array(
    'total_users' => $total_users,
    'total_documents' => $total_documents,
    'total_verified' => $total_verified,
    'total_pending' => $total_pending,
    'recent_activity' => $recent_activity
);

// Output JSON response
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
