<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

// Check if the form data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Extract billing details
    $name = $_POST["name"];
    $address = $_POST["address"];
    $floor = $_POST["floor"];
    $town = $_POST["town"];
    $number = $_POST["number"];
    $gender = $_POST["gender"];

    // Retrieve the user's email from the session (modify 'user_email' to match your session key)
    $userEmail = $_SESSION['user_email'];

    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Query to update billing details in tbl_user based on the user's email
    $updateUserSQL = "UPDATE tbl_users 
                      SET userName = ?, userAddress = ?, userStreet = ?, userCity = ?, userPhoneNumber = ?, userGender = ?
                      WHERE userEmail = ?";

    $stmtUser = $mysqli->prepare($updateUserSQL);
    $stmtUser->bind_param("sssssss", $name, $address, $floor, $town, $number, $gender, $userEmail);

    if (!$stmtUser->execute()) {
        // Handle the case where user data update fails
        echo "Failed to update billing details.";
        $stmtUser->close();
        $mysqli->close();
        exit;
    }

    // Extract item details
    $itemID = $_POST["itemID"];
    $itemPrice = $_POST["itemPrice"];
    $quantity = $_POST["quantity"];

    // Update quantity in tbl_items
    $updateQuantitySQL = "UPDATE tbl_items 
                          SET itemQuantity = itemQuantity - ?
                          WHERE itemID = ? AND itemQuantity >= ?";

    $stmtQuantity = $mysqli->prepare($updateQuantitySQL);
    $stmtQuantity->bind_param("iis", $quantity, $itemID, $quantity);

    if (!$stmtQuantity->execute()) {
        // Handle the case where quantity update fails
        echo "Failed to update item quantity.";
        $stmtQuantity->close();
        $mysqli->close();
        exit;
    }

    // Remove commas and periods from the formatted string
    $unformattedPrice = str_replace([',', '.'], '', $itemPrice);

    // Convert it back to a float
    $unformattedPrice = floatval($unformattedPrice);

    // Calculate total cost (including shipping)
    $subtotal = $unformattedPrice * $quantity;
    $shippingCost = 50;
    $total = $subtotal + $shippingCost;

    // Insert order details into tbl_order with the current date
    $insertOrderSQL = "INSERT INTO tbl_order (userID, itemID, quantity, orderDate, totalAmount, status)
            VALUES ((SELECT userID FROM tbl_users WHERE userEmail = ?), ?, ?, NOW(), ?, 'Pending')";

    $stmtOrder = $mysqli->prepare($insertOrderSQL);
    $stmtOrder->bind_param("ssss", $userEmail, $itemID, $quantity, $total);

    if (!$stmtOrder->execute()) {
        // Handle the case where order data insertion fails
        echo "Failed to insert order data.";
        $stmtOrder->close();
        $mysqli->close();
        exit;
    }

    $stmtUser->close();
    $stmtOrder->close();
    $mysqli->close();

    // Order and user data successfully saved
    header("Location: /bodyrockpc/");
} else {
    // Handle the case where the form data was not received
    echo "Form data not received.";
}
?>
