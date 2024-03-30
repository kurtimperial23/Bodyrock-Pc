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
    $supplierName = htmlspecialchars($_POST["supplierName"]);
    $supplierLocation = htmlspecialchars($_POST["supplierLocation"]);

    // Insert the new supplier into the database
    $query = "INSERT INTO tbl_supplier (supplierName, supplierLocation) VALUES (?, ?)";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "ss", $supplierName, $supplierLocation);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        // Supplier added successfully, redirect to supplier.php or show a success message
        header("Location: /bodyrockpc/admin/supplier.php");
        exit();
    } else {
        // Error adding supplier, handle accordingly (redirect, show error message, etc.)
        echo "Error: " . mysqli_error($connection);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="/Bodyrockpc/Admin/stylesdashboard.css" />
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
</head>

<body>
    <div class="sidebar">
        <!-- Dashboard Title -->
        <div class="sidebar-brand">
            <h2><span class=""></span> <span>Admin Panel</span>
            </h2>
        </div>

        <!-- Sidebar Menu -->
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="/bodyrockpc/admin/admin_dashboard.php"><span class="las la-home"></span>
                        <span>Dashboard</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/admin/items.php"><span class="las la-list"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/admin/supplier.php" class="active"><span class="las la-user-circle"></span>
                        <span>Supplier</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/admin/category.php"><span class="las la-history"></span>
                        <span>Category</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/admin/reviews.php"><span class="las la-user"></span>
                        <span>Reviews</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/admin/purchase_order.php"><span class="las la-square"></span>
                        <span>Purchase Order</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/processes/logout.php"><span class="las la-square"></span>
                        <span>Logout</span></a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <header class="d-flex justify-content-center">
            <!-- User Pic and Name -->
            <div class="user-wrapper">
            </div>
        </header>

        <main>
            <div class="container">
                <div class="ms-4 pt-5">
                    <a href="#" class="back-button" onclick="goBack(); return false;"><i
                            class="fa-solid fa-circle-arrow-left"></i> Back</a>
                </div>
                <main>
                    <!-- Add your form for adding a supplier here -->
                    <form method="post" action="/bodyrockpc/admin/admin_process/add_supplier.php">
                        <label for="supplierName">Supplier Name:</label>
                        <input type="text" name="supplierName" required>
                        <label for="supplierLocation">Supplier Location:</label>
                        <input type="text" name="supplierLocation" required>
                        <button type="submit">Add Supplier</button>
                    </form>
                </main>
                <br><br>
            </div>
        </main>
    </div>

</body>
<script src="/Bodyrockpc/scripts/script.js"></script>

</html>
