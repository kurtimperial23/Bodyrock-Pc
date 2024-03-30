<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';

if (isset($_GET['searchQuery'])) {
    $searchQuery = $_GET['searchQuery'];

    // Create a database connection
    $mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

    // Check for a database connection error
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    // Query to search for items with names containing the search query
    $searchSql = "SELECT 
                    i.itemID, 
                    i.itemName, 
                    i.itemPrice, 
                    i.itemRating, 
                    i.itemPicture, 
                    c.categoryName AS itemCategory 
                  FROM tbl_items i
                  LEFT JOIN tbl_categories c ON i.itemCategory = c.categoryID
                  WHERE i.itemName LIKE ?";

    // Prepare the statement
    $stmt = $mysqli->prepare($searchSql);

    // Bind the parameter
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("s", $searchParam);

    // Execute the query
    $stmt->execute();

    // Bind the result
    $stmt->bind_result($itemID, $itemName, $itemPrice, $itemRating, $itemImageData, $itemCategory);

    // Store the results in an array
    $results = [];
    while ($stmt->fetch()) {
        $itemPrice = number_format(intval($itemPrice), 2);

        // Convert the image data to a base64-encoded image source
        $itemImageSrc = "data:image/jpeg;base64," . base64_encode($itemImageData);

        $results[] = [
            'itemID' => $itemID,
            'itemName' => $itemName,
            'itemPrice' => $itemPrice,
            'itemRating' => $itemRating,
            'itemImageSrc' => $itemImageSrc,
            'itemCategory' => $itemCategory,
        ];
    }

    // Close the statement and connection
    $stmt->close();
    $mysqli->close();
} else {
    // Handle the case where no search query is provided
    echo "No search query provided.";
}
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
    <?php include '/xampp/htdocs/Bodyrockpc/includes/top_ad_banner.php'; ?>
    <!-- end of advertisement banner -->

    <!-- start of header -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/header.php'; ?>
    <!-- end of header -->
    <main>
        <section class="search">
            <div class="container">
                <div class="my-5">
                    <a href="/bodyrockpc/" class="back-button"><i
                            class="fa-solid fa-circle-arrow-left"></i> Back</a>
                </div>
                <div class="slider">
                    <div class="row search-items pb-5">
                        <?php
                        foreach ($results as $result) {
                            $itemID = $result["itemID"];
                            $itemName = $result["itemName"];
                            $itemPrice = $result["itemPrice"];
                            $itemRating = $result["itemRating"];
                            $itemImageSrc = $result["itemImageSrc"];
                        ?>
                        <div class="col-3 py-2">
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
            </div>
        </section>

    </main>

    <!-- start of footer -->
    <?php include '/xampp/htdocs/Bodyrockpc/includes/footer.php'; ?>
    <!-- end of footer-->
</body>
</html>
