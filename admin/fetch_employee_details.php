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

$filterType = $_GET['filter_type'] ?? 'user'; // Default to 'user' if not specified

if ($filterType === 'user') {
    // Fetch details for users
    $sql = "SELECT p.name, l.userID AS email, l.about, p.phone, p.location
            FROM profile p 
            INNER JOIN login l ON p.userID = l.userID 
            WHERE l.about = 'user'";

    $result = $conn->query($sql);

    if ($result === false) {
        echo "<tr><td colspan='5'>Error fetching data: " . $conn->error . "</td></tr>";
    } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['about']}</td>";
            echo "<td>{$row['phone']}</td>";
            echo "<td>{$row['location']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No data found</td></tr>";
    }
} elseif ($filterType === 'pca') {
    // Fetch details for PCA (assignedCA)
    $sql = "SELECT p.name, l.userID AS email, l.about
            FROM profile p 
            INNER JOIN login l ON p.userID = l.userID 
            WHERE l.about = 'PCA'";

    $result = $conn->query($sql);

    if ($result === false) {
        echo "<tr><td colspan='3'>Error fetching data: " . $conn->error . "</td></tr>";
    } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['about']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data found</td></tr>";
    }
}

$conn->close();
?>
