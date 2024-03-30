    <?php
    session_start();
    require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

    // Now you can access the database connection constants
    $dbhost = DB_HOST;
    $dbuser = DB_USER;
    $dbpass = DB_PASS;
    $dbname = DB_NAME;

    // Database connection using MySQLi
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Check for connection errors
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // initialize 
    $itemName = '';
    $itemPrice = '';
    $itemRating = '';
    $itemImageData = '';
    $itemDesc = '';

    // Check if itemID is set in the POST data
    if (isset($_POST["itemID"])) {
        // Retrieve the itemID from the POST data
        $itemID = $_POST["itemID"];

        // SQL query to select the product details for the given itemID
        $query = "SELECT * FROM tbl_items WHERE itemID = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("i", $itemID);

        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query was successful
        if ($result->num_rows > 0) {
            // Fetch the product details
            $row = $result->fetch_assoc();
            $itemID = $row["itemID"];
            $itemName = $row["itemName"];
            $itemPrice = $row["itemPrice"];
            $itemRating = $row["itemRating"];
            $itemImageData = $row["itemPicture"];
            $itemDesc = $row["itemDescription"];
            
            // Convert the image data to a base64-encoded image source
            $itemImageSrc = "data:image/jpeg;base64," . base64_encode($itemImageData);
        } else {
            // Item not found, you can handle this case accordingly
            echo "Item not found.";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // itemID is not set in the POST data, handle this case accordingly
        echo "Item ID not provided.";
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Bodyrock PC</title>

  <script>

document.addEventListener('DOMContentLoaded', function () {
    // Add a click event listener to the "Add to Cart" button
    var addToCartButton = document.getElementById('addToCartButton');
    addToCartButton.addEventListener('click', function (event) {
        event.preventDefault();

        // Get the item ID and quantity from the PHP variables
        var itemID = <?php echo $itemID; ?>;
        var quantity = parseInt(document.getElementById('quantity').textContent);

        console.log('Button clicked! Item ID:', itemID, 'Quantity:', quantity);

        // Send an AJAX request to handle the addition of the item to the cart
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/bodyrockpc/processes/add_to_cart.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Handle the response if needed
                console.log('Response:', xhr.responseText);
            }
        };

        // Send the item ID and quantity in the request body
        xhr.send('itemID=' + itemID + '&quantity=' + quantity);
    });
});
    
function buyNow() {
    // Send an AJAX request to check if the user is logged in
    $.ajax({
        type: "POST",
        url: "/bodyrockpc/processes/check_login.php",
        dataType: "json",
        success: function (response) {
            if (response.isLoggedIn) {
                // User is logged in, proceed with the "Buy Now" action
                const itemID = <?php echo $itemID; ?>;
                const itemName = '<?php echo addslashes($itemName); ?>';
                const itemPrice = <?php echo $itemPrice; ?>;
                const quantity = parseInt(document.getElementById("quantity").textContent);
                const itemImage = '<?php echo $itemImageSrc; ?>';

                // Create a form element
                const form = document.createElement('form');
                form.method = 'post';
                form.action = '/bodyrockpc/Client/checkout.php';

                // Create hidden input fields to pass the data
                const itemIDInput = document.createElement('input');
                itemIDInput.type = 'hidden';
                itemIDInput.name = 'itemID';
                itemIDInput.value = itemID;
                form.appendChild(itemIDInput);

                const itemImageInput = document.createElement('input');
                itemImageInput.type = 'hidden';
                itemImageInput.name = 'itemImage';
                itemImageInput.value = itemImage;
                form.appendChild(itemImageInput);

                const itemNameInput = document.createElement('input');
                itemNameInput.type = 'hidden';
                itemNameInput.name = 'itemName';
                itemNameInput.value = itemName;
                form.appendChild(itemNameInput);

                const itemPriceInput = document.createElement('input');
                itemPriceInput.type = 'hidden';
                itemPriceInput.name = 'itemPrice';
                itemPriceInput.value = itemPrice;
                form.appendChild(itemPriceInput);

                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = 'quantity';
                quantityInput.value = quantity;
                form.appendChild(quantityInput);

                // Append the form to the body and submit
                document.body.appendChild(form);
                form.submit();
            } else {
                // User is not logged in, redirect to the login page
                window.location.href = '/bodyrockpc/login.php';
            }
        },
        error: function (error) {
            console.error("Error:", error);
        },
    });
}

document.addEventListener('DOMContentLoaded', function () {
    // Add a click event listener to the "Add to Cart" button
    var addToCartButton = document.getElementById('addToCartButton');
    addToCartButton.addEventListener('click', function (event) {
        event.preventDefault();
        showConfirmationModal('Item has been added to the cart.');
    });

    // Function to show the confirmation modal
    function showConfirmationModal(message) {
        var confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
        var modalBody = document.getElementById('confirmationModalBody');
        modalBody.textContent = message;
        confirmationModal.show();
    }
});

document.getElementById('place-order-btn').addEventListener('click', function () {
    submitForm();
});

  </script>

    </head>

    <body>
        <!-- start of advertisement banner -->
        <?php include '/xampp/htdocs/Bodyrockpc/includes/top_ad_banner.php'; ?>
        <!-- end of advertisement banner -->

        <!-- start of header -->
        <?php include '/xampp/htdocs/Bodyrockpc/includes/header.php'; ?>
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
                        Item has been added to cart.
                    </div>
                    <div class="modal-footer">
                        <!-- Only the "Close" button is present -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

            <section class="product_details">
                <div class="container">
                    <div class="my-5">
                        <a href="#" class="back-button" onclick="goBack(); return false;"><i class="fa-solid fa-circle-arrow-left"></i> Back</a>
                    </div>

                    <div class="row d-flex justify-content-between align-items-center">
                        <div class="col-6">
                            <div class="img-wrapper d-flex align-items-center justify-content-center">
                                <img src="<?php echo $itemImageSrc; ?>" alt="img" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row flex-column">
                                <div class="col ps-0">
                                    <h1><?php echo"$itemName"; ?></h1>
                                </div>
                                <div class="col ps-0 product_desc rating">
                                        <?php
                                        // Generate star icons based on itemRating
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $itemRating) {
                                                echo '<i class="fa-solid fa-star"></i>';
                                            } else {
                                                echo '<i class="fa-regular fa-star" style="color:grey"></i>';
                                            }
                                        }
                                        ?>
                                        <h2>(0 reviews)</h2>
                                        <h2>|</h2><span>In stock</span>
                                    </div>
                                    
                                <div class="col ps-0 my-3">
                                    <h3>₱<?php
                                    $itemPrice = number_format($itemPrice, 2); 
                                    echo"$itemPrice"; ?></h3>
                                </div>
                                <div class="col ps-0">
                                    <p><?php echo"$itemDesc"; ?></p>
                                </div>
                                <hr class="ps-0">

                                <div class="row ps-3">
                                    <div class="col-3 counter-container d-flex justify-content-center align-items-center">
                                        <button class="counter-button" onclick="decrementQuantity()"><i class="fa-solid fa-minus"></i></button>
                                        <span id="quantity" class="d-flex justify-content-center"> 1 </span>
                                        <button class="counter-button" onclick="incrementQuantity()"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                    <div class="col-3 d-flex justify-content-center align-items-center ms-2">
                                        <button type="button" class="btn btn-danger" onclick="buyNow()" id="buyNowButton">Buy Now</button>
                                    </div>
                                    <div class="col-4 d-flex justify-content-center align-items-center">
                                        <button type="button" id="addToCartButton" class="btn btn-outline-dark">Add to Cart</button>
                                    </div>
                                    <div class="col-1">
                                        <div class="add-to-wishlist d-flex justify-content-center">
                                            <i class="fa-regular fa-heart"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- start of footer -->
        <?php include '/xampp/htdocs/Bodyrockpc/includes/footer.php'; ?>
        <!-- end of footer  -->
    </body>

    </html>