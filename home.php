<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}
;

include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home | Page</title>
   <!-- LINK IN FAVICON -->
   <link rel="shortcut icon" href="favicon/user.png" type="image/x-icon">
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

<?php include 'components/user_header.php';?>

<!-- HEADER END -->


<!-- HOME START -->
<section class="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">

         <div class="swiper-slide slide">
            <div class="content">
               <span>Order Online</span>
               <h3>Delicious Burger!</h3>
               <a href="menu.php" class="btn">Order Now!!</a>
            </div>
            <div class="image">
               <img src="images/banner1.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Order Online</span>
               <h3>Taste adobo!</h3>
               <a href="menu.php" class="btn">Order Now!!</a>
            </div>
            <div class="image">
               <img src="images/banner2.png" alt="">
            </div>
         </div>

         <div class="swiper-slide slide">
            <div class="content">
               <span>Order Online</span>
               <h3>Sweet Desserts!</h3>
               <a href="menu.php" class="btn">Order Now!!</a>
            </div>
            <div class="image">
               <img src="images/banner3.png" alt="">
            </div>
         </div>

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

<!-- CATEGORY STARTS -->
<section class="category">
   <h1 class="title">FOOD CATEGORY</h1>

   <div class="box-container">

      <a href="category.php?category=fast food" class="box">
         <img src="images/cat-1.png" alt="">
         <h3>Fast Food</h3>
      </a>

      <a href="category.php?category=main dish" class="box">
         <img src="images/cat-2.png" alt="">
         <h3>Main Dish</h3>
      </a>

      <a href="category.php?category=drinks" class="box">
         <img src="images/cat-3.png" alt="">
         <h3>Drinks</h3>
      </a>

      <a href="category.php?category=desserts" class="box">
         <img src="images/cat-4.png" alt="">
         <h3>Desserts</h3>
      </a>

   </div>
</section>
<!-- CATEGORY END -->



<!-- PRODUCTS STARTS -->
<section class="products">

   <h1 class="title">Display Food</h1>

   <div class="box-container">

      <?php
        $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 3");
        $select_products->execute();
        if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?=$fetch_products['id'];?>">
         <input type="hidden" name="name" value="<?=$fetch_products['name'];?>">
         <input type="hidden" name="price" value="<?=$fetch_products['price'];?>">
         <input type="hidden" name="image" value="<?=$fetch_products['image'];?>">
         <img src="uploaded_img/<?=$fetch_products['image'];?>" alt="">
         <a href="category.php?category=<?=$fetch_products['category'];?>" class="cat"><?=$fetch_products['category'];?></a>
         <div class="name"><?=$fetch_products['name'];?></div>
      </form>
      <?php
}
} else {
    echo '<p class="empty">NO FOOD AVAILABLE!</p>';
}
?>

   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn">View All</a>
   </div>

</section>
<!-- PRODUCT ENDS -->








<!-- INCLUDING FOOTER -->
<?php include 'components/footer.php';?>
<!-- FOOTER ENDS -->

<!-- SWIPER JS  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>

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