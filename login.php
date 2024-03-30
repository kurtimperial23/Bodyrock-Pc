<?php
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

<!-- start of main -->
<main>
    <section class="loginform">
          <div class="container py-5">
        <div class="row d-flex justify-content-center">
            <div class="col-5">
                <img src="/bodyrockpc/images/Frame123.png" class="img-fluid" alt="">
            </div>
            <div class="col-5">
                <div class="wrapper p-5 mt-4">
                    <h1>Login to Account</h1>
                    <div>
                        <h6>Enter your details below</h6>
                    </div>
                    <form action="/bodyrockpc/Processes/login_process.php" method="post">
                        <div class="row flex-column d-flex justify-content-left">
                            <div class="col">
                                <input type="text" name="txtemail" id="email" placeholder="Email or Phone Number" />
                            </div>
                            <div class="col">
                                <input type="password" name="txtpass" id="pass" placeholder="Password" />
                            </div>
                            <div class="col button d-flex align-items-center">
                                <button type="submit" class="btn btn-danger mt-4">Login</button>
                            </div>
                            <div class="col text-end pt-2">
                               <a href="">Forgot Password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </section>
  
</main>
<!-- end of main  -->
    
<!-- start of footer -->
<?php include '/xampp/htdocs/Bodyrockpc/includes/footer.php';?>
<!-- end of footer  --> 
</body>
</html>