<?php
session_start();

require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

// Now you can access the database connection constants
$dbhost = DB_HOST;
$dbuser = DB_USER;
$dbpass = DB_PASS;
$dbname = DB_NAME;

// Database connection
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Fetch categories from tbl_categories
$sql = "SELECT categoryID, categoryName FROM tbl_categories";
$result = $mysqli->query($sql);

// Fetch random items from tbl_items
$sql1 = "SELECT itemID, itemName, itemPrice, itemRating, itemPicture FROM tbl_items ORDER BY RAND() LIMIT 6";
$result1 = $mysqli->query($sql1);

// Fetch random items from tbl_items
$sql2 = "SELECT itemID, itemName, itemPrice, itemRating, itemPicture FROM tbl_items ORDER BY RAND() LIMIT 16";
$result2 = $mysqli->query($sql2);
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Bodyrock PC</title>
    </head>
    <body>
        <!-- start of advertisement banner -->
        <?php include '/xampp/htdocs/Bodyrockpc/includes/top_ad_banner.php';?>
        <!-- end of advertisement banner -->

        <!-- start of header -->
        <?php include '/xampp/htdocs/Bodyrockpc/includes/header.php';?>
        <!-- end of header -->

        <!-- start of main banner -->
<section class="main_banner">
    <div class="container">
        <div class="row">
            <div class="col-2">
                <div class="col-content pt-5">
                    <ul>
                        <?php
                    if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Output a list item for each category
                                echo '<li>' . htmlspecialchars($row['categoryName']) . '</li>';
                            }
                        } else {
                            echo "<li>No categories found</li>.";
                        }

                        // Close the database connection
                        $mysqli->close();
                        ?>
                    </ul>
                </div>
            </div>

            <div class="col-10 d-flex justify-content-center">
                <img class="img-fluid pt-5" src="/Bodyrockpc/images/allProductsBanner.png" alt="" >
            </div>
        </div>
    </div>
</section>
        <!-- end of main banner -->

        <!-- start of main content -->
<main>
    <section class="today_highlight" id="landing_page">
        <div class="container">
            <div class="row d-flex flex-column pb-4">
                <div class="col d-flex align-items-center pb-4">
                    <div class="vertical-rectangle"></div>
                    <span>Today's Highlight</span>
                </div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-6">
                            <h1>Flash Sales</h1>
                        </div>
                        <div class="col-6 text-end">
                            <a><i class="fa-solid fa-arrow-left arrow-left"></i></a>
                            <a><i class="fa-solid fa-arrow-right arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider">
                <div class="row multiple-items pb-5">
                <?php
                    while ($row = $result1->fetch_assoc()) {
                        $itemID = $row["itemID"];
                        $itemName = $row["itemName"];
                        $itemPrice = $row["itemPrice"];
                        $itemRating = $row["itemRating"];
                        $itemImageData = $row["itemPicture"];

                        $itemPrice = number_format(intval($itemPrice), 2);

                        // Convert the image data to a base64-encoded image source
                        $itemImageSrc = "data:image/jpeg;base64," . base64_encode($itemImageData);
                    ?>
                <div class="col-2 py-2">
                    <form action="/bodyrockpc/Client/product_details.php" method="post">
                        <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
                        <div class="product-wrapper">
                            <div class="product-img d-flex align-items-center justify-content-center">
                                <button type="submit" class="image-button">
                                    <img src="<?php echo $itemImageSrc; ?>" alt="img" class="">
                                </button>
                            </div>
                            <div class="add-to-cart">
                                <a href=""><i class="fa-regular fa-heart"></i></a>
                            </div>
                        </div>
                    
                    <div class="product-details">
                        <div class="row">
                            <div class="col">
                                <h1><?php echo $itemName; ?></h1>
                                <h2>₱ <?php echo $itemPrice; ?></h2>
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
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                </div>
                    <?php
                    }
                    ?>
                </div>
            </div>

            <div class="text-center">
            <a href="/bodyrockpc/client/all_products.php" class="btn btn-danger px-5 py-3">View All Products</a>

            </div>
        </div>
    </section>

    <div class="container mb-5 pb-5">
        <hr>
    </div>

    <!-- <section class="category_highlight">
        <div class="container">
            <div class="row d-flex flex-column pb-4">
                <div class="col d-flex align-items-center pb-4">
                    <div class="vertical-rectangle"></div>
                    <span>Categories</span>
                </div>
                <div class="col-10">
                    <div class="row">
                        <div class="col-6">
                            <h1>Browse By Category</h1>
                        </div>
                        <div class="col-6 text-end">
                            <a><i class="fa-solid fa-arrow-left arrow-left arrow-left3"></i></a>
                            <a><i class="fa-solid fa-arrow-right arrow-right arrow-right3"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider">
                <div class="row multiple-items3 d-flex pb-3">
                    <div class="col">Slide 1</div>
                    <div class="col">Slide 2</div>
                    <div class="col">Slide 3</div>
                    <div class="col">Slide 4</div>
                </div>
            </div>
        </div>
    </section> -->
<!-- 
    <div class="container my-5 py-4">
        <hr>
    </div> -->

    <section class="explore_products">
        <div class="slider">
            <div class="container">
                <div class="row d-flex flex-column pb-4">
                    <div class="col d-flex align-items-center pb-4">
                        <div class="vertical-rectangle"></div>
                        <span>Our Products</span>
                    </div>
                    <div class="col-10">
                        <div class="row">
                            <div class="col-6">
                                <h1>Explore Our Products</h1>
                            </div>
                            <div class="col-6 text-end">
                                <a><i class="fa-solid fa-arrow-left arrow-left1"></i></a>
                                <a><i class="fa-solid fa-arrow-right arrow-right1"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="slider">
                    <div class="row multiple-items1 pb-5">
                    <?php
                        while ($row1 = $result2->fetch_assoc()) {
                            $itemID = $row1["itemID"];
                            $itemName = $row1["itemName"];
                            $itemPrice = $row1["itemPrice"];
                            $itemRating = $row1["itemRating"];
                            $itemImageData = $row1["itemPicture"];

                            $itemPrice = number_format(intval($itemPrice), 2);

                            // Convert the image data to a base64-encoded image source
                            $itemImageSrc = "data:image/jpeg;base64," . base64_encode($itemImageData);
                        ?>
                    <div class="col-2 py-2">
                        <form action="/bodyrockpc/Client/product_details.php" method="post">
                            <input type="hidden" name="itemID" value="<?php echo $itemID; ?>">
                            <div class="product-wrapper">
                                <div class="product-img d-flex align-items-center justify-content-center">
                                    <button type="submit" class="image-button">
                                        <img src="<?php echo $itemImageSrc; ?>" alt="img" class="">
                                    </button>
                                </div>
                                <div class="add-to-cart">
                                    <a href=""><i class="fa-regular fa-heart"></i></a>
                                </div>
                            </div>
                        
                        <div class="product-details">
                            <div class="row">
                                <div class="col">
                                    <h1><?php echo $itemName; ?></h1>
                                    <h2>₱ <?php echo $itemPrice; ?></h2>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="text-center">
                <a href="/bodyrockpc/client/all_products.php" class="btn btn-danger px-5 py-3">View All Products</a>
                </div>
            </div>
        </div>

    </section>

    <section class="feature">
        <div class="container">
            <div class="row d-flex justify-content-center text-center">
                <div class="col-3">
                    <img src="/bodyrockpc/images/delivery.png" alt="">
                    <div>
                        <h1>FREE AND FAST DELIVERY</h1>
                        <p>Free delivery for all orders over ₱10,000</p>
                    </div>
                </div>
                <div class="col-3">
                    <img src="/bodyrockpc/images/service.png" alt="">
                    <div>
                        <h1>24/7 CUSTOMER SERVICE</h1>
                        <p>Friendly 24/7 customer support</p>
                    </div>
                </div>
                <div class="col-3">
                    <img src="/bodyrockpc/images/guarantee.png" alt="">
                    <div>
                        <h1>MONEY BACK GUARANTEE    </h1>
                        <p>We return money within 30 days</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

        <!-- end of main content -->


        <!-- start of footer -->
<?php include '/xampp/htdocs/Bodyrockpc/includes/footer.php';?>
        <!-- end of footer  -->   
</body>
</html>
