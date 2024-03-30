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

// Query to fetch items from tbl_items
$sql = "SELECT 
            i.itemID, 
            i.itemName, 
            i.itemDescription, 
            i.itemPrice, 
            i.itemRating, 
            s.supplierName AS itemSupplier, 
            i.itemPicture, 
            i.itemDateBought,
            i.itemQuantity,
            c.categoryName AS itemCategory 
        FROM tbl_items i
        LEFT JOIN tbl_supplier s ON i.itemSupplier = s.supplierID
        LEFT JOIN tbl_categories c ON i.itemCategory = c.categoryID";


// Execute the query
$result = $mysqli->query($sql);

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
                    <a href="/bodyrockpc/admin/items.php" class="active"><span class="las la-list"></span>
                        <span>Items</span></a>
                </li>
                <li>
                    <a href="/bodyrockpc/admin/supplier.php"><span class="las la-user-circle"></span>
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
                    <h2>Items</h2>
                </div>
            </div>
        </header>
        <main class="pt-5">
            <div class="container mt-2">
                <!-- Display items in a table -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Rating</th>
                            <th>Supplier</th>
                            <th>Picture</th>
                            <th>Date Bought</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Fetch and display items data in the table
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["itemID"] . "</td>";
                                echo "<td>" . $row["itemName"] . "</td>";
                                echo "<td>" . $row["itemDescription"] . "</td>";
                                echo "<td>" . number_format((float)$row["itemPrice"], 2) . "</td>"; // Cast to float
                                echo "<td>" . $row["itemRating"] . "</td>";
                                echo "<td>" . $row["itemSupplier"] . "</td>";
                                
                                // Convert itemPicture to a data URL
                                $dataUrl = "data:image/jpeg;base64," . base64_encode($row["itemPicture"]);
                                echo "<td><img src='" . $dataUrl . "' alt='Item Picture' width='100'></td>";
                                
                                echo "<td>" . $row["itemDateBought"] . "</td>";
                                echo "<td>" . $row["itemCategory"] . "</td>";
                                echo "<td>" . $row["itemQuantity"] . "</td>";
                                echo "<td>  
                                    <button class='btn btn-danger btn-lg' data-bs-toggle='modal' data-bs-target='#confirmationModal' onclick='setDeleteItemsId(" . $row["itemID"] . ")'>Delete</button>
                                    <a class='btn btn-primary btn-lg' href='/bodyrockpc/admin/admin_process/edit_items.php?id=" . $row["itemID"] . "'>Edit</a>
                                  </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Prompt message for the user -->
                        <p>Are you sure you want to delete this Item?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Send request to delete_category.php -->
                        <form method="post" action="/bodyrockpc/admin/admin_form_handler/delete_item.php">
                            <input type="hidden" name="deleteItems" id="deleteItemsId" value="">
                            <button type="submit" class="btn btn-primary">Yes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</body>
<script>
    function setDeleteItemsId(itemsId) {
        document.getElementById('deleteItemsId').value = itemsId;
    }
</script>
</html>