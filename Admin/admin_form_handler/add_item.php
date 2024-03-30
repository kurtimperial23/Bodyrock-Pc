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

// Establish database connection
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $itemName = htmlspecialchars($_POST["itemName"]);
    $itemDescription = htmlspecialchars($_POST["itemDescription"]);
    $itemPrice = floatval($_POST["itemPrice"]);
    $itemRating = intval($_POST["itemRating"]);
    $itemDateBought = $_POST["itemDateBought"];
    $itemQuantity = intval($_POST["itemQuantity"]);
    $itemCategory = intval($_POST["itemCategory"]);
    $itemSupplier = intval($_POST["itemSupplier"]);

    // Handle file upload in binary format
    $itemPicture = file_get_contents($_FILES["itemPicture"]["tmp_name"]);

    if ($_FILES["itemPicture"]["error"] == UPLOAD_ERR_OK) {
        // File uploaded successfully
    } else {
        // Handle file upload error
        echo "File upload failed with error code: " . $_FILES["itemPicture"]["error"];
    }
    

    // Insert the new item into the database
    $query = "INSERT INTO tbl_items (itemName, itemDescription, itemPrice, itemRating, itemPicture, itemDateBought, itemQuantity, itemCategory, itemSupplier) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Use prepared statement to handle BLOB data
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ssdibsiii", $itemName, $itemDescription, $itemPrice, $itemRating, $itemPicture, $itemDateBought, $itemQuantity, $itemCategory, $itemSupplier);
    

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Item added successfully, redirect to items.php or show a success message
        header("Location: /bodyrockpc/admin/items.php");
        exit();
    } else {
        // Error adding item, handle accordingly (redirect, show error message, etc.)
        echo "Error: " . mysqli_error($connection);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($connection);
?>
