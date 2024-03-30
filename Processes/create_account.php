<?php
// Include the database configuration file
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

// Now you can access the database connection constants
$dbhost = DB_HOST;
$dbuser = DB_USER;
$dbpass = DB_PASS;
$dbname = DB_NAME;

// Initialize variables to store user input
$name = $email = $password = "";
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $name = trim($_POST["txtuser"]);
    $email = trim($_POST["txtemail"]);
    $password = $_POST["txtpass"];

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Default user role
    $userRole = "user";

    // Perform database insertion if data is valid
    if (!empty($name) && !empty($email) && !empty($password)) {
        // Database connection
        $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if the email already exists in the database
        $checkStmt = $mysqli->prepare("SELECT userEmail FROM tbl_users WHERE userEmail = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkStmt->close();

        if ($checkResult->num_rows > 0) {
            $error = "Email already exists. Please use a different email.";
        } else {
            // Email is unique, proceed with insertion
            $insertStmt = $mysqli->prepare("INSERT INTO tbl_users (userName, userEmail, userPass, userRole) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $name, $email, $hashedPassword, $userRole);

            if ($insertStmt->execute()) {
                // Registration successful, redirect to a success page or login page
                header("Location: /bodyrockpc/");
                exit();
            } else {
                $error = "Registration failed. Please try again later.";
            }

            $insertStmt->close();
        }

        // Close the database connection
        $mysqli->close();
    } else {
        $error = "Please fill in all the required fields.";
    }
}
?>
