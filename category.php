<?php

// INCLUDING DATABASE CONNECTION
include 'components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

// INCLUDING ADD TO CART QUERY
include 'components/add_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Food Category</title>

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

<section class="products">
  <h1 class="title">Food Category</h1>
  <div class="box-container">
    <?php
      if (isset($_GET['category'])) {
        $category = $_GET['category'];
        $get_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
        $get_products->execute([$category]);
        $products = $get_products->fetchAll(PDO::FETCH_ASSOC);

        if (count($products) > 0) {
          foreach ($products as $product) {
           ?>
            <form action="" method="post" class="box">
            <button style="color:white; font-weight: 900; padding: 5px; margin-bottom: 5px; font-size:20px; background-color:#E0163D; border-radius: 40px; cursor:pointer;" type="button" onclick="location.href='home.php';">Go Back</button>
              <input type="hidden" name="pid" value="<?= $product['id'] ?>">
              <input type="hidden" name="name" value="<?= $product['name'] ?>">
              <input type="hidden" name="price" value="<?= $product['price'] ?>">
              <input type="hidden" name="image" value="<?= $product['image'] ?>">
              <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
              <img src="uploaded_img/<?= $product['image'] ?>" alt="">
              <h1 class="category"><?= $product['category'] ?></h1>
              <div class="name"><?= $product['name'] ?></div>
              <div class="flex">
                <div class="price"><span>₱</span><?= $product['price'] ?></div>
                <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
              </div>
            </form>
        <?php
          }
        } else {
          echo '<p class="empty">Sorry no food available!</p>';
        }
      } else {
        echo '
        <style>
        .category .box-container {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(216px, 1fr));
          gap: 12px;
          align-items: flex-start;
      }
      
      .category .box-container .box {
          background-color: white;
          border-radius: 20px;
          border: 2px solid #231F20;
          box-shadow: 0 0 0 2px rgba(224, 22, 61, 0.2),
              0 0 0 4px rgba(224, 22, 61, 0.1),
              0 0 20px rgba(224, 22, 61, 0.1);
          padding: 16px;
          text-align: center;
      }
      
      .category .box-container .box img {
          width: 100%;
          height: 80px;
          object-fit: contain;
      }
      
      .category .box-container .box h3 {
          font-size: 16px;
          margin-top: 12px;
          color: #231F20;
          text-transform: capitalize;
      }
      
      .category .box-container .box:hover {
          background-color: #231F20;
      }
      
      .category .box-container .box:hover img {
          filter: invert(1);
      }
      
      .category .box-container .box:hover h3 {
          color: #fff;
      }
      
      </style>
        <section class="category">
        <div class="box-container">
     
           <a href="category.php?category=fast food" class="box">
              <img src="images/cat-1.png" alt="">
              <h3 style="text-align: center;">Fast Food</h3>
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
     </section>';
      }
    ?>
  </div>
</section>


<div class="loading">
    <img src="images/loading.gif" alt="">
</div>


<!-- SWIPER JS  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>


</body>
</html>