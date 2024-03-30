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

// Get category ID from the URL parameter
$categoryID = $_GET['id'];

// Query to fetch category details by ID
$sql = "SELECT categoryID, categoryName FROM tbl_categories WHERE categoryID = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($categoryID, $categoryName);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>

    <link rel="stylesheet" href="/Bodyrockpc/Admin/stylesdashboard.css" />
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
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
                    <a href="/bodyrockpc/admin/admin_dashboard.php"><span
                            class="las la-home"></span>
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
                <div>
                    <h2>Edit Category</h2>
                </div>
            </div>
        </header>
        <main class="pt-5">
            <div class="ms-4 pt-5">
                <a href="#" class="back-button" onclick="goBack(); return false;"><i class="las la-arrow-left"></i> Back</a>
            </div>  
            <div class="container mt-5">
                <!-- Display category details in a form for editing -->
                <div class="row">
                    <div class="col-4">
                        <form method="post" action="/bodyrockpc/admin/admin_form_handler/edit_category_process.php">
                            <input type="hidden" name="categoryID" value="<?php echo $categoryID; ?>">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Category Name:</label>
                                <input type="text" class="form-control" id="categoryName" name="categoryName" value="<?php echo $categoryName; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

<script>
    function goBack() {
        // Go back one step in the browsing history
        window.history.back();
    }
</script>

</html>
