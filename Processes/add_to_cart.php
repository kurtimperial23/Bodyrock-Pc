<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

if (isset($_POST['itemID'])) {
    // Get the item ID from the AJAX request
    $itemID = $_POST['itemID'];

    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        $userID = $_SESSION['user_id'];
    } else {
        // If not logged in, use the session ID as a temporary identifier
        $userID = session_id();
    }

    // Database connection using MySQLi
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Check if a cart exists for the user
    $cartID = null;
    $selectCartSQL = "SELECT cartID FROM tbl_cart WHERE userID = ?";
    $stmtSelectCart = $mysqli->prepare($selectCartSQL);
    $stmtSelectCart->bind_param("s", $userID);
    $stmtSelectCart->execute();
    $stmtSelectCart->bind_result($cartID);
    $stmtSelectCart->fetch();
    $stmtSelectCart->close();

    if (!$cartID) {
        // If no cart exists, create a new cart
        $insertCartSQL = "INSERT INTO tbl_cart (userID) VALUES (?)";
        $stmtInsertCart = $mysqli->prepare($insertCartSQL);
        $stmtInsertCart->bind_param("s", $userID);
        $stmtInsertCart->execute();
        $stmtInsertCart->close();

        // Retrieve the newly created cart ID
        $cartID = $mysqli->insert_id;
    }

    // Add the item to the cart_items table
    $insertCartItemSQL = "INSERT INTO tbl_cart_item (cartID, itemID, quantity, dateAdded) VALUES (?, ?, 1, NOW()) ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmtInsertCartItem = $mysqli->prepare($insertCartItemSQL);
    $stmtInsertCartItem->bind_param("ss", $cartID, $itemID);
    $stmtInsertCartItem->execute();
    $stmtInsertCartItem->close();

    // Retrieve cart details (you can customize this part)
    $cartDetails = [];
    $selectCartDetailsSQL = "SELECT ci.cartItemID, i.itemName, i.itemPicture, i.itemPrice, ci.quantity, (i.itemPrice * ci.quantity) AS subtotal
                             FROM tbl_cart_item ci
                             JOIN tbl_items i ON ci.itemID = i.itemID
                             WHERE ci.cartID = ?";
    $stmtSelectCartDetails = $mysqli->prepare($selectCartDetailsSQL);
    $stmtSelectCartDetails->bind_param("s", $cartID);
    $stmtSelectCartDetails->execute();
    $result = $stmtSelectCartDetails->get_result();

    while ($row = $result->fetch_assoc()) {
        $cartDetails[] = $row;
    }

    $stmtSelectCartDetails->close();

    // Close the database connection
    $mysqli->close();

    // Send a response to the AJAX request
    echo json_encode($cartDetails);
} else {
    // If itemID is not set in the request, send an error response
    echo "Error: itemID not provided in the request";
}
?>
