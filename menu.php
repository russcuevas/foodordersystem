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
   <title>Food Menu | Page</title>

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

<div class="heading">
   <h3>Our Menu</h3>
   <p><a href="home.php">Home</a> <span> / Food Menu</span></p>
</div>


<!-- FOOD MENU STARTS -->
<section class="products">
   <h1 class="title">Available Food</h1>
   <form action="" method="get" class="search-form">
      <input type="text" name="search" placeholder="Search here..">
      <button type="submit" class="fas fa-search"></button>
   </form>

   <div class="box-container">
      <?php
      // Set up pagination variables
      $limit = 6; // Number of products to display per page
      $page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
      $start = ($page - 1) * $limit; // Starting point for displaying products

      // SEARCH QUERY
      if (isset($_GET['search'])) {
         $search = $_GET['search'];
         $count_products = $conn->prepare("SELECT COUNT(*) FROM `products` WHERE `name` LIKE '%$search%' OR `category` LIKE '%$search%'");
         $select_products = $conn->prepare("SELECT * FROM `products` WHERE `name` LIKE '%$search%' OR `category` LIKE '%$search%' LIMIT $start, $limit");
      } else {
         $count_products = $conn->prepare("SELECT COUNT(*) FROM `products`");
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT $start, $limit");
      }

         $count_products->execute();
         $total = $count_products->fetchColumn();

         $select_products->execute();

         if ($select_products->rowCount() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               ?>
            <form action="" method="post" class="box">
                  <input type="hidden" name="pid" value="<?=$fetch_products['id'];?>">
                  <input type="hidden" name="name" value="<?=$fetch_products['name'];?>">
                  <input type="hidden" name="price" value="<?=$fetch_products['price'];?>">
                  <input type="hidden" name="image" value="<?=$fetch_products['image'];?>">
                  <a href="quick_view.php?pid=<?=$fetch_products['id'];?>" class="fas fa-eye"></a>
                  <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
                  <img src="uploaded_img/<?=$fetch_products['image'];?>" alt="">
                  <a href="category.php?category=<?=$fetch_products['category'];?>" class="cat"><?=$fetch_products['category'];?></a>
                  <div class="name"><?=$fetch_products['name'];?></div>
                  <div class="flex">
                     <div class="price"><span>₱</span><?=$fetch_products['price'];?></div>
                     <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
                  </div>
            </form>
         <?php
            }
            } else {
               echo '<p class="empty">No Products Found!</p>';
            }
         ?>
   </div>
      <div class="pagination-container">
         <ul class="pagination">

         <?php
            $total_pages = ceil($total / $limit);
            $start_page = max($page - 2, 1);
            $end_page = min($start_page + 4, $total_pages);

            if ($page > 1) {
               echo '<li><a href="?page=' . ($page - 1) . '" style="color:#E0163D;"> &lt;&lt; </a></li>';
            }

            for ($i = $start_page; $i <= $end_page; $i++) {
               if ($i == $page) {
                  echo '<li class="active">' . $i . '</li>';
               } else {
                  echo '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
               }
            }

            if ($page < $total_pages) {
               echo '<li><a href="?page=' . ($page + 1) . '" style="color:#E0163D;"> &gt;&gt; </a></li>';
            }
            ?>

         </ul>
      </div>
</section>

<!-- FOOD MENU ENDS -->

<!-- FOR LOADING PAGE -->
<div class="loading">
    <img src="images/loading.gif" alt="">
</div>
<!-- END OF LOADING -->


<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>

</body>
</html>