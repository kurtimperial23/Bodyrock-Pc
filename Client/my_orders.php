<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

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

// Fetch orders for the user
$sql = "SELECT o.orderID, i.itemName, i.itemPicture, i.itemPrice, o.quantity, (i.itemPrice * o.quantity) AS subtotal, o.orderDate, o.totalAmount, o.status
        FROM tbl_order o
        JOIN tbl_items i ON o.itemID = i.itemID
        WHERE o.userID = ?";

$stmt = $mysqli->prepare($sql);

// Bind parameters using variables as references
$stmt->bind_param("s", $userID);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- start of advertisement banner -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/top_ad_banner.php';?>
    <!-- end of advertisement banner -->

    <!-- start of header -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/header.php';?>
    <!-- end of header -->

    <main>
        <section class="cart">
            <div class="container">
                <div class="mt-5">
                    <a href="#" class="back-button" onclick="goBack(); return false;"><i class="fa-solid fa-circle-arrow-left"></i> Back</a>
                </div>  
                <table class="table table-hover table-fixed">
                    <thead>
                        <tr>
                            <th scope="col">Picture</th>
                            <th scope="col">Product</th>
                            <th scope="col">Date Bought</th>                            
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through the fetched orders and generate table rows
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['itemPicture']); ?>" alt="Product Image"></td>
                                <td><?php echo $row['itemName']; ?></td>
                                <td><?php echo $row['orderDate']; ?></td>
                                <td><?php echo number_format($row['itemPrice'], 2); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo number_format($row['subtotal'], 2); ?></td>
                                <td><?php echo $row['status']; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

                <div class="buttons">
                    <div class="row">
                        <div class="col">
                            <button type="button" href="/bodyrockpc/" class="btn btn-outline-dark">Return to Homepage</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- start of footer -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/footer.php';?>
    <!-- end of footer  -->   
</body>
</html>
