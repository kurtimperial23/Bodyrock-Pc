<?php
session_start();
require_once '/xampp/htdocs/Bodyrockpc/includes/includes.php';
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
        <section class="wishlist">
            <div class="container">
                <div class="my-5">
                        <a href="#" class="back-button" onclick="goBack(); return false;"><i class="fa-solid fa-circle-arrow-left"></i> Back</a>
                    </div>      
                <div class="row d-flex flex-column">
                    <div class="col">
                        <h1>Wishlist()</h1>
                    </div>
                    <div class="col">
                        <div class="wishlist_list">
                            <div class="row">
                                <div class="col-3">
                                    <div class="product-wrapper">
                                        <div class="product-img d-flex align-items-center justify-content-center">
                                            <img src="/bodyrockpc/images/image63.png" alt="img" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">Slide 2</div>
                                <div class="col-3">Slide 3</div>
                                <div class="col-3">Slide 4</div>
                                <div class="col-3">Slide 5</div>
                                <div class="col-3">Slide 6</div> 
                                <div class="col-3">Slide 7</div>
                                <div class="col-3">Slide 8</div>
                                <div class="col-3">Slide 9</div>
                                <div class="col-3">Slide 10</div>
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