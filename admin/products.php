<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

// ADD PRODUCT QUERIES

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $price = $_POST['price'];
   $category = $_POST['category'];

   $image = $_FILES['image']['name'];
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->execute([$name]);

   if($select_products->rowCount() > 0){
      $message[] = 'Product name already exists!';
   }else{
      if($image_size > 2000000){
         $message[] = 'Image size is too large';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);

         $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
         $insert_product->execute([$name, $category, $price, $image]);

         $message[] = 'NEW PRODUCT ADDED!!';
      }

   }

}

// DELETE PRODUCT QUERIES

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->execute([$delete_id]);
   $fetch_delete_image = $delete_product_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Display Food</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- ADD PRODUCT START -->

<section class="add-products">

   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add Food</h3>
      <input type="text" required placeholder="Food name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Food Price ₱" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Select Category --</option>
         <option value="Fast Food">Fast Food</option>
         <option value="Main Dish">Main Dish</option>
         <option value="Drinks">Drinks</option>
         <option value="Desserts">Desserts</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png," required>
      <input type="submit" value="Add Food" name="add_product" class="btn">
   </form>

</section>

<!-- ADD PRODUCT END -->


<!-- SEARCH START -->
<section class="search-section">
   <form action="" method="get" class="search-form">
      <input type="text" name="search" placeholder="Search...">
      <button type="submit"><i class="fa fa-search"></i></button>
   </form>
</section>

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">
   
      <?php
         $limit = 6; // number of items to show per page
         $page = isset($_GET['page']) ? $_GET['page'] : 1; // get current page from URL
         $offset = ($page - 1) * $limit; // calculate offset for SQL query

         // check if search query is set
         if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $count_products = $conn->prepare("SELECT COUNT(*) FROM `products` WHERE `name` LIKE '%$search%' OR `category` LIKE '%$search%'");
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE `name` LIKE '%$search%' OR `category` LIKE '%$search%' LIMIT $offset, $limit");
         } else {
            $count_products = $conn->prepare("SELECT COUNT(*) FROM `products`");
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT $offset, $limit");
         }
         
         $count_products->execute();
         $total_items = $count_products->fetchColumn(); // count total items in the table
         $total_pages = ceil($total_items / $limit); // calculate total pages needed
         
         $select_products->execute();
         if($select_products->rowCount() > 0){
            while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){  
      ?>
      <div class="box">
         <img src="../uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <div class="flex">
            <div class="price"><span>₱</span><?= $fetch_products['price']; ?><span></span></div>
            <div class="category">Category <br> <?= $fetch_products['category']; ?></div>
         </div>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this food?');">Delete</a>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">No Products Found!</p>';
         }
      ?>
      
   </div>
   
<div class="pagination-container">
   <ul class="pagination">
      
   <?php
      if ($total_pages > 1) { // display pagination if more than one page
         // link to previous page
         if ($page > 1) {
            echo '<li><a href="?page='.($page - 1).'" style="color:#E0163D;"> &lt;&lt; </a></li>';
         }

         for ($i=1; $i<=$total_pages; $i++) {
            if ($i == $page) {
               echo '<li class="active">'.$i.'</a></li>';
            } else {
               echo '<li><a href="?page='.$i.'">'.$i.'</a></li>';
            }
         }

         // link to next page
         if ($page < $total_pages) {
            echo '<li><a href="?page='.($page + 1).'" style="color:#E0163D;"> &gt;&gt; </a></li>';
         }
      }
   ?>
   </ul>
</div>



</section>


<!-- SHOW PRODUCTS END -->



<!-- CUSTOM ADMIN JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>