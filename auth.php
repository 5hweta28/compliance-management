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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'signup') {
        // Sign-Up Code
        $fullname = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $pca_user = $_POST['pca-user'];
        $about = $pca_user === "yes" ? "PCA" : "USER";

        // Check if email already exists
        $sql_check_email = "SELECT * FROM login WHERE userID='$email'";
        $result_check_email = $conn->query($sql_check_email);
        
        if ($result_check_email->num_rows > 0) {
            echo "Email already exists. Please use a different email.";
        } else {
            // Insert new user into login table
            $sql_insert_login = "INSERT INTO login (userID, name, password, about) VALUES ('$email', '$fullname', '$password', '$about')";
            
            if ($conn->query($sql_insert_login) === TRUE) {
                // Insert into profile table with other fields as NULL
                $sql_insert_profile = "INSERT INTO profile (userID, name) VALUES ('$email', '$fullname')";
                $conn->query($sql_insert_profile); // This assumes other fields (phone, location, pic_link) are NULL by default

                header("Location: index.html"); // Redirect to successful sign-up page
                exit();
            } else {
                echo "Error: " . $sql_insert_login . "<br>" . $conn->error;
            }
        }

    } elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'signin') {
        // Sign-In Code
        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($email === "admin@gmail.com" && $password === "admin@123") {
            header("Location: admin\index.html");
            exit();
        }

        $sql = "SELECT * FROM login WHERE userID='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                session_start();
                $_SESSION['userID'] = $row['userID'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['about'] = $row['about'];

                if ($row['about'] == 'PCA') {
                    header("Location: pca\index.html");
                } else {
                    header("Location: user\index.html");
                }
                exit();
            } else {
                echo "Invalid password";
            }
        } else {
            echo "No account found with that email";
        }
    }

    $conn->close();
}
?>
