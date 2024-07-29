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

// Get the posted data
$data = json_decode(file_get_contents("php://input"), true);

$document_type = $data['document_type'];
$file_name = $data['file_name'];
$supplier_name = $data['supplier_name'];
$document_number = $data['document_number'];
$purchase_date = $data['purchase_date'];
$due_date = $data['due_date'];
$total_amount = $data['total_amount'];
$doc_image = $data['doc_image'];

// Remove the base64 metadata (e.g., "data:image/jpeg;base64,")
$doc_image = preg_replace('#^data:image/[^;]+;base64,#', '', $doc_image);
$doc_image = base64_decode($doc_image);

// Prepare the SQL statement
$sql_doc = $conn->prepare("INSERT INTO doc (doc_num, doctype, filename, supplier_name, purchase_date, due_date, total_amount, doc_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$sql_doc->bind_param('ssssssds', $document_number, $document_type, $file_name, $supplier_name, $purchase_date, $due_date, $total_amount, $doc_image);

if ($sql_doc->execute()) {
    $docID = $conn->insert_id;
    // Insert into task table with status as "Pending" and completion_status as "Not Done"
    $sql_task = $conn->prepare("INSERT INTO task (doc_name, type, doc_num, date, userID, status, completion_status) VALUES (?, ?, ?, ?, ?, 'Pending', 'Not Done')");
    $sql_task->bind_param('sssss', $file_name, $document_type, $document_number, $due_date, $userID);

    if ($sql_task->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => $conn->error]);
}

$conn->close();
?>
