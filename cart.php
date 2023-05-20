<?php

// INCLUDING DATABASE CONNECTION
include 'components/connect.php';


// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:home.php');
};


// DELETE CART QUERY
if(isset($_POST['delete'])){
   $cart_id = $_POST['cart_id'];

   // EXECUTING
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->execute([$cart_id]);
   $message[] = '• Cart item deleted!';
}

// DELETE ALL FROM CART QUERY
if(isset($_POST['delete_all'])){
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->execute([$user_id]);
   $message[] = '• Deleted all from cart!';
}

// UPDATE QUANTITY QUERY
if(isset($_POST['update_qty'])){
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];

   // EXECUTING
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->execute([$qty, $cart_id]);
   $message[] = '• Cart quantity updated';
}

$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>My Cart</title>
   <!-- LINK IN FAVICON -->
   <link rel="shortcut icon" href="favicon/user.png" type="image/x-icon">
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
   <h3>Shopping Cart</h3>
   <p><a href="home.php">Home</a> <span> / Cart</span></p>
</div>

<!-- SHOPPING CART START -->

<section class="products">

   <h1 class="title">My Cart</h1>

   <div class="box-container">

      <?php
         $grand_total = 0;
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
      ?>
      <form action="" method="post" class="box">
            <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
            <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('Are you sure you want to delete this food?');"></button>
            <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="">
            <div class="name"><?= $fetch_cart['name']; ?></div>
            <div class="flex">
            <div class="price"><span>₱</span><?= $fetch_cart['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
         <div class="sub-total"> Sub Total : <span>₱<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?></span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         }else{
            echo '<p class="empty">YOUR CART IS EMPTY!</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>Cart Total : <span>₱<?= $grand_total; ?></span></p>
      <a href="checkout.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">Proceed to checkout</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" name="delete_all" onclick="return confirm('Are you sure you want to delete all from cart?');">Delete All</button>
      </form>
      <a href="menu.php" class="btn">Continue Shopping</a>
   </div>

</section>

<!-- SHOPPING CART ENDS -->




<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>

</body>
</html>