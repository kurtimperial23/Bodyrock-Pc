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

// Handle deletion of supplier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["deleteSupplier"])) {
    $deleteSupplierID = $_POST["deleteSupplier"];

    // Perform deletion query
    $sql = "DELETE FROM tbl_supplier WHERE supplierID = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $deleteSupplierID);

    if ($stmt->execute()) {
        // Supplier deleted successfully
        header("Location: /bodyrockpc/admin/supplier.php");
        exit();
    } else {
        // Error deleting supplier
        echo "Error: " . $mysqli->error;
    }

    $stmt->close();
}

// Close the database connection
$mysqli->close();
?>
