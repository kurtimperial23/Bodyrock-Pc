$(document).ready(function(){
// FLASH SALE
    // Initialize Slick Slider
    $('.multiple-items').slick({
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        prevArrow: $('.arrow-left'),
        nextArrow: $('.arrow-right'),   
    });

    // Click event handler for left arrow
    $('.arrow-left').on('click', function() {
        $(this).toggleClass('clicked'); // Toggle the 'clicked' class
        setTimeout(function() {
            $('.arrow-left').removeClass('clicked'); // Remove 'clicked' class
        },100); // Adjust the duration (milliseconds) as needed
    });

    // Click event handler for right arrow
    $('.arrow-right').on('click', function() {
        $(this).toggleClass('clicked'); // Toggle the 'clicked' class
        setTimeout(function() {
            $('.arrow-right').removeClass('clicked'); // Remove 'clicked' class
        }, 100); // Adjust the duration (milliseconds) as needed
    });
});

$(document).ready(function(){
    // CATEGORY
        // Initialize Slick Slider
        $('.multiple-items1').slick({
            infinite: true,
            slidesToShow: 4,
            rows: 2,   
            slidesToScroll: 1,
            prevArrow: $('.arrow-left1'),
            nextArrow: $('.arrow-right1'),
        });
    
        // Click event handler for left arrow
        $('.arrow-left1').on('click', function() {
            $(this).toggleClass('clicked'); // Toggle the 'clicked' class
            setTimeout(function() {
                $('.arrow-left1').removeClass('clicked'); // Remove 'clicked' class
            }, 200); // Adjust the duration (milliseconds) as needed
        });
    
        // Click event handler for right arrow
        $('.arrow-right1').on('click', function() {
            $(this).toggleClass('clicked'); // Toggle the 'clicked' class
            setTimeout(function() {
                $('.arrow-right1').removeClass('clicked'); // Remove 'clicked' class
            }, 200); // Adjust the duration (milliseconds) as needed
        });
    
    });

    

// dropdown menu script 
$(document).ready(function() {
    // Hide the dropdown menu initially
    $("#user-dropdown").hide();

    // Toggle the dropdown menu when clicking the user icon
    $("#user-icon").click(function(e) {
        e.preventDefault();
        $("#user-dropdown").toggle();
    });

    // Close the dropdown menu when clicking anywhere else on the page
    $(document).click(function(e) {
        if (!$(e.target).closest("#user-icon, #user-dropdown").length) {
            $("#user-dropdown").hide();
        }
    });
});


function goBack() {
    // Go back one step in the browsing history
    window.history.back();
}


let quantity = 1; // Initial quantity

function decrementQuantity() {
    if (quantity > 1) {
        quantity--;
        updateQuantityDisplay();
    }
}

function incrementQuantity() {
    quantity++;
    updateQuantityDisplay();
}

function updateQuantityDisplay() {
    document.getElementById("quantity").textContent = quantity;
}


// Function to update the quantity displayed on the page
function updateQuantity() {
    const quantityElement = document.getElementById("quantity");
    quantityElement.textContent = quantity;
}

document.addEventListener("DOMContentLoaded", function () {
    // Add click event listeners to elements with the login-required class
    const loginRequiredElements = document.querySelectorAll(".login-required");
    loginRequiredElements.forEach(function (element) {
        element.addEventListener("click", function (event) {
            // Check if the user is not logged in (adjust this condition as needed)
            if (!userIsLoggedIn) {
                // Prevent the default action (e.g., following a link)
                event.preventDefault();
                // Redirect to the login page
                window.location.href = "/bodyrockpc/Client/create_account.php";
            }
        });
    });
});


function calculateTotal() {
    const itemPrice = "<?php echo $itemPrice; ?>";
    const quantity = parseInt(document.getElementById("quantity").textContent);
    const total = itemPrice * quantity;

    // Update the total element in the HTML
    document.getElementById("total").textContent = '₱' + total.toFixed(2); // Format total with two decimal places
}

// Call the calculateTotal function initially
calculateTotal();

