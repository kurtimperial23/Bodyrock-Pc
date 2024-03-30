<script>
    const userIsLoggedIn = <?php echo isset($_SESSION["user_email"]) ? "true" : "false"; ?>;
</script>

<header id="client">
        <nav>
            <div class="container text-center">
                <div class="row">
                    <div class="col-6 d-flex align-items-center">
                        <a href="/bodyrockpc/"><h1>Bodyrock PC</h1></a>
                    </div>
                    <div class="col-6 search_group">
                    <form action="/bodyrockpc/client/search.php" >
                        <div class="input-group ">
                                <input
                                type="text"
                                class="form-control"
                                name="searchQuery"
                                placeholder="What are you looking for?"
                                aria-label="What are you looking for?"
                                aria-describedby="basic-addon2"
                            />
                            <button class="btn btn-outline-secondary" type="submit">
                                 <i class="fa-solid fa-magnifying-glass px-3"></i>  
                            </button>

                            </form>
                            
                            <a href="/bodyrockpc/Client/wishlist.php" class="d-flex justify-content-center align-items-center text-center login-required">
                                <i class="fa-regular fa-heart d-flex justify-content-center align-items-center text-center ps-4"></i>
                            </a>
                            <a class="d-flex justify-content-center align-items-center text-center ps-4 login-required" href="/bodyrockpc/Client/cart.php">
                                <i class="fa-solid fa-cart-shopping d-flex justify-content-center align-items-center text-center"></i>
                            </a>
                            <div class="d-flex justify-content-center align-items-center text-center ps-4">
                                <i class="fa-regular fa-user" id="user-icon"></i>
                                <div class="dropdown-menu" id="user-dropdown">
                                    <a href="#" class="login-required">Manage my account</a>
                                    <a href="/bodyrockpc/client/my_orders.php" class="login-required">My Orders</a>
                                    <a href="#" class="login-required">My Cancellations</a>
                                    <a href="#" class="login-required">My Reviews</a>
                                    <a href="/bodyrockpc/Processes/logout.php" class="login-required">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
<hr>