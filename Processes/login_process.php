<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

// Now you can access the database connection constants
$dbhost = DB_HOST;
$dbuser = DB_USER;
$dbpass = DB_PASS;
$dbname = DB_NAME;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["txtemail"];
    $password = $_POST["txtpass"];

    // Database connection
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Prepare and execute the SQL statement to fetch user data
    $stmt = $mysqli->prepare("SELECT userID, userName, userEmail, userPass, userRole FROM tbl_users WHERE userEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $dbPassword = $row["userPass"];

        // Verify the user's password
        if (password_verify($password, $dbPassword)) {
            // Password is correct, set session variables
            $_SESSION["user_id"] = $row["userID"];
            $_SESSION["user_email"] = $row["userEmail"];
            $_SESSION["user_name"] = $row["userName"];
            $_SESSION["user_role"] = $row["userRole"];

            // Check if the user is an admin
            if ($_SESSION["user_role"] == "admin") {
                // Redirect admin to the admin dashboard
                header("Location: /bodyrockpc/Admin/admin_dashboard.php");
                exit();
            } else {
                // Redirect non-admin users to the home page
                header("Location: /bodyrockpc/");
                exit();
            }
        } else {
            // Password is incorrect, display an error message
            echo "Invalid password.";
        }
    } else {
        // User not found in the database, display an error message
        echo "User not found.";
    }

    // Close the database connection
    $mysqli->close();
}
?>
