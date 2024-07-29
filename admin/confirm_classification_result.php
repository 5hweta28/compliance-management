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

$doc_num = $_POST['doc_num'];
$doctype = $_POST['doctype'];
$filename = $_POST['filename'];
$supplier_name = $_POST['supplier_name'];
$purchase_date = $_POST['purchase_date'];
$due_date = $_POST['due_date'];
$total_amount = $_POST['total_amount'];

// Update doc table
$sql_doc = "UPDATE doc SET doctype = ?, filename = ?, supplier_name = ?, purchase_date = ?, due_date = ?, total_amount = ? WHERE doc_num = ?";
$stmt_doc = $conn->prepare($sql_doc);
$stmt_doc->bind_param("sssssss", $doctype, $filename, $supplier_name, $purchase_date, $due_date, $total_amount, $doc_num);
$stmt_doc->execute();
$stmt_doc->close();

// Update task table
$sql_task = "UPDATE task SET doc_name = ?, type = ?, date = ?, status = 'verified' WHERE doc_num = ?";
$stmt_task = $conn->prepare($sql_task);
$stmt_task->bind_param("ssss", $filename, $doctype, $due_date, $doc_num);
$stmt_task->execute();
$stmt_task->close();

$response = array('success' => true);
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
