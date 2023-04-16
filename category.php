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
              <input type="hidden" name="pid" value="<?= $product['id'] ?>">
              <input type="hidden" name="name" value="<?= $product['name'] ?>">
              <input type="hidden" name="price" value="<?= $product['price'] ?>">
              <input type="hidden" name="image" value="<?= $product['image'] ?>">
              <a href="quick_view.php?pid=<?= $product['id'] ?>" class="fas fa-eye"></a>
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
        header('location:home.php');
      }
    ?>
  </div>
</section>


















<!-- INCLUDING FOOTER -->
<?php include 'components/footer.php';?>
<!-- FOOTER ENDS -->


<!-- SWIPER JS  -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>


</body>
</html>