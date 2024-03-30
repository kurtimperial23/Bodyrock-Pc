<?php
session_start();
require_once '/xampp/htdocs/bodyrockpc/includes/db_config.php';

// Check if the user is logged in and has the admin role
if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] === "admin") {
    // User is an admin, allow access to the page
} else {
    // User is not an admin, redirect to index.php
    header("Location: /bodyrockpc/index.php");
    exit();
}

// Create a database connection
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check for a database connection error
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $supplierID = $_POST["supplierID"];
    $supplierName = htmlspecialchars($_POST["supplierName"]);
    $supplierLocation = htmlspecialchars($_POST["supplierLocation"]);

    // Update the supplier details in the database
    $sql = "UPDATE tbl_supplier SET supplierName = ?, supplierLocation = ? WHERE supplierID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssi", $supplierName, $supplierLocation, $supplierID);
    $result = $stmt->execute();

    if ($result) {
        // Supplier updated successfully, redirect to supplier.php or show a success message
        header("Location: /bodyrockpc/admin/supplier.php");
        exit();
    } else {
        // Error updating supplier, handle accordingly (redirect, show error message, etc.)
        echo "Error: " . $mysqli->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>
