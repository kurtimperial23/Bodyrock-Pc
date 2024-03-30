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

// Fetch items from tbl_items
$sql = "SELECT itemName, itemPrice, itemRating, itemPicture FROM tbl_items";
$result = $mysqli->query($sql);
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
    <!-- start of main banner -->
    <section class="main_banner">
        <div class="container ">
            <div class="row">
                <div class="col-12">
                    <img class="img-fluid pt-5" src="/Bodyrockpc/images/allProductsBanner.png" alt="" >
                </div>
            </div>
        </div>
    </section>
        <!-- end of main banner -->

        <div class="container mb-4   mt-5 pt-3">
            <hr>
        </div>

        <section class="explore_products">
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
                </div>
            </div>
        </div>
        <div class="product_list">
            <div class="row">
                <?php
                // Loop through the fetched items and generate HTML for each item
                while ($row = $result->fetch_assoc()) {
                    $itemName = $row["itemName"];
                    $itemPrice = $row["itemPrice"];
                    $itemRating = $row["itemRating"];
                    
                    // Fetch the item image data from the database
                    $itemImageData = $row["itemPicture"];
                    
                    // Convert the image data to a base64-encoded image source
                    $itemImageSrc = "data:image/jpeg;base64," . base64_encode($itemImageData);
                ?>
                <div class="col-3 mb-5">
                    <div class="product-wrapper">
                        <div class="product-img d-flex align-items-center justify-content-center">
                            <img src="<?php echo $itemImageSrc; ?>" alt="img" class="img-fluid">
                        </div>
                        <div class="add-to-cart">
                            <a href=""><i class="fa-regular fa-heart"></i></a>
                        </div>
                        <div class="product-details">
                            <div class="row">
                                <div class="col">
                                    <h1><?php echo $itemName; ?></h1>
                                    <h2><?php echo $itemPrice; ?></h2>
                                    <div class="col ps-0 product_desc rating">
                                        <?php
                                        // Generate star icons based on itemRating
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $itemRating) {
                                                echo '<i class="fa-solid fa-star"></i>';
                                            } else {
                                                echo '<i class="fa-regular fa-star" style="color:black"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
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
