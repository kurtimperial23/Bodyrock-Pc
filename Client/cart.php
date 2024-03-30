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

// Fetch cart items for the user
$sql = "SELECT ci.cartItemID, i.itemName, i.itemPicture, i.itemPrice, ci.quantity, (i.itemPrice * ci.quantity) AS subtotal
        FROM tbl_cart_item ci
        JOIN tbl_items i ON ci.itemID = i.itemID
        WHERE ci.cartID IN (
            SELECT cartID FROM tbl_cart WHERE userID = ? OR userID = ? 
        )";
$stmt = $mysqli->prepare($sql);

// Bind parameters using variables as references
$userIDParam = $userID;
$stmt->bind_param("ss", $userIDParam, $userIDParam);

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
                            <th scope="col">Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through the fetched cart items and generate table rows
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['itemPicture']); ?>" alt="Product Image"></td>
                                <td><?php echo $row['itemName']; ?></td>
                                <td><?php echo number_format($row['itemPrice'], 2); ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo number_format($row['subtotal'], 2); ?></td>
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

            <div class="container pt-5">
                <div class="row d-flex justify-content-between">
                    <div class="col-6">
                        <div class="input-group">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Coupon Code"
                                aria-label="Coupon Code"
                                aria-describedby="basic-addon2"
                            />
                            <button type="button" class="btn btn-danger px-5 py-3">Apply Coupon</button>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="total-price">
                            <h2>Cart Total</h2>
                            <div class="price_total">
                                <h5>Subtotal</h5>
                                <?php
                                    $subtotal = 0;
                                    // Loop through the fetched cart items to calculate the subtotal
                                    while ($row = $result->fetch_assoc()) {
                                        $subtotal += $row['itemPrice'];
                                    }
                                    echo number_format($subtotal, 2);
                                ?>
                            </div>
                            <hr>
                            <div class="price_total">
                                <h5>Shipping</h5>
                                <?php
                                    // You can set a fixed shipping cost or calculate it based on your business logic
                                    $shippingCost = 50.00;
                                    echo number_format($shippingCost, 2);
                                ?> 
                            </div>
                            <hr>
                            <div class="price_total">
                                <h5>Total</h5>
                                <?php
                                    $total = $subtotal + $shippingCost;
                                    echo number_format($total, 2);
                                ?>
                            </div>

                            <div>
                                <a href="/bodyrockpc/Client/checkout.php"><button type="button" class="btn btn-danger px-5 py-3">Proceed To Checkout</button></a>
                            </div>
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