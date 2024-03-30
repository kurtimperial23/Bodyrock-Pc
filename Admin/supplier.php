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

// Query to fetch suppliers from tbl_supplier
$sql = "SELECT supplierID, supplierName, supplierLocation FROM tbl_supplier";

// Execute the query
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier</title>

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
                    <h2>Supplier</h2>
                </div>
            </div>
        </header>
        <main class="pt-5">
            <div class="container mt-5">
                <!-- Display suppliers in a table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Supplier Name</th>
                            <th>Supplier Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display supplier data in the table
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["supplierID"] . "</td>";
                            echo "<td>" . $row["supplierName"] . "</td>";
                            echo "<td>" . $row["supplierLocation"] . "</td>";
                            echo "<td class='text-center'>  
                                    <button class='btn btn-danger btn-lg' data-bs-toggle='modal' data-bs-target='#confirmationModal' onclick='setDeleteSupplierId(" . $row["supplierID"] . ")'>Delete</button>

                                    <a class='btn btn-primary btn-lg' href='/bodyrockpc/admin/admin_process/edit_supplier.php?id=" . $row["supplierID"] . "'>Edit</a>

                                  </td>";
                            
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteConfirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Prompt message for the user -->
                        <p>Are you sure you want to delete this supplier?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Send request to delete_supplier.php -->
                        <form method="post" action="/bodyrockpc/admin/admin_form_handler/delete_supplier.php">
                            <input type="hidden" name="deleteSupplier" id="deleteSupplierId" value="">
                            <button type="submit" class="btn btn-primary">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>  
    <script>
    function setDeleteSupplierId(supplierId) {
        document.getElementById('deleteSupplierId').value = supplierId;
        // Show the delete confirmation modal
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        deleteModal.show();
    }

    </script>
</html>
