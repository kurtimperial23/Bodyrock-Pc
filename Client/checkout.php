<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

// Check if the required data is set in the POST request
if (isset($_POST['itemID'], $_POST['itemName'], $_POST['itemPrice'], $_POST['quantity'], $_POST['itemImage'])) {
    $itemID = $_POST['itemID'];
    $itemName = $_POST['itemName'];
    $itemPrice = $_POST['itemPrice'];
    $quantity = $_POST['quantity'];
    $itemImage = $_POST['itemImage'];
} else {
    // Handle the case where the required data is not set, for example, redirect to an error page
    header('Location: error.php');
    exit;
}

$subtotal = $itemPrice * $quantity;
$total = $subtotal + 50;

$itemPrice = number_format($itemPrice, 2);
$subtotal = number_format($subtotal, 2);
$total = number_format($total, 2);

// Add code to fetch user data from the database based on the logged-in user's email
$userEmail = $_SESSION['user_email'];

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query to fetch user data from the database based on email
$selectUserDataSQL = "SELECT userName, userAddress, userStreet, userCity, userPhoneNumber, userGender FROM tbl_users WHERE userEmail = ?";
$stmtUserData = $mysqli->prepare($selectUserDataSQL);
$stmtUserData->bind_param("s", $userEmail);
$stmtUserData->execute();
$stmtUserData->bind_result($userName, $userAddress, $userStreet, $userCity, $userPhoneNumber, $userGender);

if ($stmtUserData->fetch()) {
    // User data found, assign it to variables for use in input fields
} else {
    // User not found, handle the error as needed
    echo "User not found.";
    $stmtUserData->close();
    $mysqli->close();
    exit;
}

$stmtUserData->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <script>
// Function to handle the form submission
function submitForm() {
    // Get the data from the billing form
    var name = document.getElementById('name').value;
    var address = document.getElementById('address').value;
    var floor = document.getElementById('floor').value;
    var town = document.getElementById('town').value;
    var number = document.getElementById('number').value;
    var gender = document.getElementById('gender').value;

    // Set the data in the payment form
    document.getElementById('txtname').value = name;
    document.getElementById('txtaddress').value = address;
    document.getElementById('txtfloor').value = floor;
    document.getElementById('txttown').value = town;
    document.getElementById('txtnumber').value = number;
    document.getElementById('txtgender').value = gender;

    // Show a modal with only the "Close" button
    var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    confirmationModal.show();
}

// Attach an event listener to the "Place Order" button
document.getElementById('place-order-btn').addEventListener('click', function () {
    submitForm();
});

// Function to handle the form submission after the modal is closed
function confirmSubmission() {
    // Submit the form
    document.getElementById('payment-form').submit();
}

// Attach an event listener to the modal's hidden.bs.modal event (fires after the modal is closed)
document.getElementById('confirmationModal').addEventListener('hidden.bs.modal', function () {
    confirmSubmission();
});
</script>
</head>
<body>

    <input type="hidden" id="quantity" value="<?php echo $quantity; ?>">

    <!-- start of advertisement banner -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/top_ad_banner.php';?>
    <!-- end of advertisement banner -->

    <!-- start of header -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/header.php';?>
    <!-- end of header -->

    <main>
        <div class="modal" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel">Confirm Submission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Your order has been placed succesfully.
                    </div>
                    <div class="modal-footer">
                        <!-- Only the "Close" button is present -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <section class="checkout">
            <div class="container">
            <a href="/bodyrockpc/" class="back-button"><i
                            class="fa-solid fa-circle-arrow-left"></i> Cancel</a>
                <h1>Billing Details</h1>
                <div class="row d-flex justify-content-start pt-3">
                    <div class="col-4">
                        <div class="row d-flex flex-column">
                            <div class="col">
                                <form id="billing-form">
                                    <div class="row flex-column d-flex justify-content-left">
                                        <div class="col">
                                            <label for="name">Name <span>*</span></label>
                                            <input type="text" name="name" id="name" value="<?php echo $userName; ?>"/>
                                        </div>
                                        <div class="col">
                                            <label for="address">Street Address <span>*</span></label>
                                            <input type="text" name="address" id="address" value="<?php echo $userAddress; ?>" />
                                        </div>
                                        <div class="col">
                                            <label for="floor">Apartment, Floor, etc.</label>
                                            <input type="text" name="floor" id="floor" value="<?php echo $userStreet; ?>" />
                                        </div>
                                        <div class="col">
                                            <label for="town">Town / City <span>*</span></label>
                                            <input type="text" name="town" id="town" value="<?php echo $userCity; ?>" />
                                        </div>
                                        <div class="col">
                                            <label for="number">Phone Number <span>*</span></label>
                                            <input type="number" name="number" id="number" value="<?php echo $userPhoneNumber; ?>"/>
                                        </div>
                                        <div class="col">
                                            <label for="gender">Gender</label>
                                            <input type="text" name="gender" id="gender" value="<?php echo $userGender; ?>"/>
                                        </div>
                                        <div class="col button d-flex align-items-center ps-3">
                                            <span><input type="checkbox" name="email_num" id="email_num" <?php echo isset($userName) ? 'checked' : ''; ?> /></span> &nbsp;<p> &nbsp;Save this information for faster checkout next time</p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-5">
                        <div class="total-price-checkout">
                            <div class="row mb-2">
                                <div class="p-0 col-2">
                                    <img src="<?php echo $itemImage; ?>" alt="Product Image" class="img-fluid">
                                </div>
                                <div class="p-0 col-7">
                                    <h5><?php echo $itemName; ?></h5>
                                </div>
                                <div class="p-0 col-1">
                                    <h5><?php echo $quantity; ?>x</h5>
                                </div>
                                <div class="p-0 col-1 mr-1">
                                    <h5>₱<?php echo $itemPrice; ?> </h5>
                                </div>
                            </div>
                            <div class="price_total d-flex justify-content-between">
                                <h5>Subtotal</h5> <h5>₱ <?php echo $subtotal; ?> </h5>
                            </div>
                            <hr>
                            <div class="price_total d-flex justify-content-between">
                               <h5>Shipping</h5> <h5>₱ 50.00</h5>
                            </div>
                            <hr>
                            <div class="price_total d-flex justify-content-between">
                                <div><h5>Total:</h5></div>
                                <div><span>₱ <?php echo $total;?></span></div>
                                
                            </div>
                            <div>
                                <form id="payment-form" action="/bodyrockpc/processes/checkout_process.php" method="post">
                                <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
                                <input type="hidden" name="itemName" value="<?php echo $itemName; ?>">
                                <input type="hidden" name="itemPrice" value="<?php echo $itemPrice; ?>">
                                <input type="hidden" name="quantity" value="<?php echo $quantity; ?>">  
                                <input type="hidden" name="name" id="txtname" value="name"/>
                                <input type="hidden" name="address" id="txtaddress" />
                                <input type="hidden" name="floor" id="txtfloor" />
                                <input type="hidden" name="town" id="txttown" />
                                <input type="hidden" name="number" id="txtnumber"/>
                                <input type="hidden" name="gender" id="txtgender"/>
                                   <div class="px-2">
                                        <input type="radio" name="payment" value="bank">
                                        <label for="">Bank</label>
                                   </div>
                                   <div class="px-2">
                                        <input type="radio" checked name="payment" value="cash_on_delivery">
                                        <label for="">Cash on Delivery</label>
                                   </div>
                                   <div class="text-center">
                                        <button type="submit" class="btn btn-danger px-5 py-3" onclick="submitForm()">Place Order</button>
                                    </div>
                                </form>
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