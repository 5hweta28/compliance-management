<?php
// Start session (if not already started)
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

// Get logged-in user's email from session (assuming userID is stored in session)
if (!isset($_SESSION['userID'])) {
    die("User not logged in");
}

$assignedCA = $_SESSION['userID'];

// Fetch counts
$assignedCount = $conn->query("SELECT COUNT(*) as count FROM task WHERE assignedCA = '$assignedCA'")->fetch_assoc()['count'];
$pendingCount = $conn->query("SELECT COUNT(*) as count FROM task WHERE assignedCA = '$assignedCA' AND status = 'pending'")->fetch_assoc()['count'];
$completedCount = $conn->query("SELECT COUNT(*) as count FROM task WHERE assignedCA = '$assignedCA' AND completion_status = 'done'")->fetch_assoc()['count'];

// Fetch recent documents
$sql = "SELECT userID, doc_name, doc_num, type, status, completion_status 
        FROM task 
        WHERE assignedCA = '$assignedCA' 
        ORDER BY date DESC 
        LIMIT 10";
$result = $conn->query($sql);

$recentDocs = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recentDocs[] = array(
            'userID' => $row['userID'],
            'doc_name' => $row['doc_name'],
            'doc_num' => $row['doc_num'],
            'type' => $row['type'],
            'status' => $row['status'],
            'completion_status' => $row['completion_status']
        );
    }
}

// Output data as JSON
header('Content-Type: application/json');
echo json_encode(array(
    'assigned_count' => $assignedCount,
    'pending_count' => $pendingCount,
    'completed_count' => $completedCount,
    'recent_docs' => $recentDocs
));

$conn->close();
?>
