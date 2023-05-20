<?php

// INCLUDING DATABASE CONNECTION
include 'components/connect.php';

// SESSION START
session_start();

// SESSION IF THE USER IS LOGIN
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!-- ABOUT US PAGE -->
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" type="image/png" href="icon.svg">
   <title>About | Page</title>
   <!-- LINK IN FAVICON -->
   <link rel="shortcut icon" href="favicon/icon.svg" type="image/x-icon">
    <!-- SWIPER LINK -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- CSS LINK  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- INCLUDING HEADER -->
<?php include 'components/user_header.php'; ?>
<!-- HEADER ENDS -->


<!-- BREADCRUMBS TITLE -->
    <div class="heading">
        <h3>About Us</h3>
        <p><a href="home.php">Home</a> <span> / About</span></p>
    </div>
<!-- BREADCRUMBS END -->

<!-- ABOUT SECTION STARTS -->

    <section class="about">
        <div class="row">
            <div class="image">
                <img src="images/aboutus.jpg" alt="">
            </div>
            <div class="content">
                <h3>Why Choose Us?</h3>
                <p>We are happy to serve you delicious food that suits your personal taste, family preferences, cultural influences, emotional reasons, health concerns, societal pressures, convenience, cost, and variety and quantity of the available offerings. All these factors come into play when you choose what to eat.</p>
                <a href="menu.html" class="btn">Order Now!</a>
            </div>
        </div>
    </section>

<!-- ABOUT SECTION END -->

<!-- SERVICES START -->
    <section class="service">
        <h1 class="title">Featured Service</h1>
        <div class="box-container">
            <div class="box">
                <img src="images/services-1.png" alt="">
                <h3>Online Order</h3>
                <p>Choose your favorite dish, dessert, or drink.</p>
            </div>

            <div class="box">
                <img src="images/services-2.png" alt="">
                <h3>Fast Delivery</h3>
                <p>We are going to deliver your food fast</p>
            </div>

            <div class="box">
                <img src="images/services-3.png" alt="">
                <h3>Enjoying foods</h3>
                <p>We hope you enjoy our food products:)</p>
            </div>
        </div>
    </section>
<!-- SERVICES ENDS -->

<!-- REVIEWS START -->
    <section class="reviews">
        <h1 class="title">Customer's Reviews</h1>

        <div class="swiper reviews-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide">
                    <img src="images/customer1.png" alt="">
                    <p>Taste good! I'll comeback again to order and eat their delicious food!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>Javie De Leon</h3>
                </div>

                <div class="swiper-slide slide">
                    <img src="images/customer2.png" alt="">
                    <p>Taste good! I'll comeback again to order and eat their delicious food!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>Dave De Leon</h3>
                </div>

                <div class="swiper-slide slide">
                    <img src="images/customer3.png" alt="">
                    <p>Taste good! I'll comeback again to order and eat their delicious food!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>Yvan Kalalo</h3>
                </div>

                <div class="swiper-slide slide">
                    <img src="images/customer4.png" alt="">
                    <p>Taste good! I'll comeback again to order and eat their delicious food!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>James Dimaalihan</h3>
                </div>

                <div class="swiper-slide slide">
                    <img src="images/customer5.png" alt="">
                    <p>Taste good! I'll comeback again to order and eat their delicious food!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>Jeno Orongan</h3>
                </div>

                <div class="swiper-slide slide">
                    <img src="images/customer6.png" alt="">
                    <p>Taste good! I'll comeback again to order and eat their delicious food!</p>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <h3>Alfrancis Castillo</h3>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
<!-- REVIEWS END -->



















<!-- INCLUDING FOOTER -->
<?php include 'components/footer.php';?>
<!-- FOOTER ENDS -->

<!-- SWIPER JS  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   grabCursor: true,
   spaceBetween: 20,
   autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
      slidesPerView: 1,
      },
      700: {
      slidesPerView: 2,
      },
      1024: {
      slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>