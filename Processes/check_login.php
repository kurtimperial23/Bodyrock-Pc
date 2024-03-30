<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION["user_id"]);

// Return a JSON response
header('Content-Type: application/json');
echo json_encode(['isLoggedIn' => $isLoggedIn]);
?>
