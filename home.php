<?php 

// INCLUDING DATABASE
include 'components/connect.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- SWIPER LINK -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM CSS LINK -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<!-- HEADER STARTS -->
<!-- INCLUDING THE USER HEADER FILE -->
<?php include 'components/user_header.php' ?>
<!-- HEADER ENDS -->

    <!-- HEADER ENDS -->

    <!-- BANNER STARTS -->
    <section class="home">
        <div class="swiper home-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Order Online</span>
                        <h3>Delicious burger!</h3>
                        <a href="menu.php" class="btn">Order now!</a>
                    </div>
                    <div class="image">
                        <img src="../images/banner1.png" alt="">
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Order Online</span>
                        <h3>Taste adobo!</h3>
                        <a href="menu.php" class="btn">Order now!</a>
                    </div>
                    <div class="image">
                        <img src="../images/banner2.png" alt="">
                    </div>
                </div>

                <div class="swiper-slide slide">
                    <div class="content">
                        <span>Order Online</span>
                        <h3>Sweet Desserts!</h3>
                        <a href="menu.php" class="btn">Order now!</a>
                    </div>
                    <div class="image">
                        <img src="../images/banner3.png" alt="">
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
    <!-- BANNER ENDS -->


<!-- FOOTER STARTS -->
<!-- INCLUDING THE FOOTER IN COMPONENTS -->
<?php include 'components/footer.php'; ?>
<!-- FOOTER SECTION ENDS -->

<!-- SWIPER JS  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<!-- CUSTOM JS USER FILE -->
<script src="../js/script.js"></script>

<script>
        var swiper = new Swiper(".home-slider", {
            loop: true,
            grabCursor: true,
            effect: "flip",
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });
    </script>
</body>
</html>