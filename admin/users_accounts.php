<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

// DELETE QUERIES FOR USER ACCOUNT ALL IN (CART, ORDER)
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->execute([$delete_id]);
    header('location:users_accounts.php');
 }
 
 ?>
 
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Account</title>
 
     <!-- FONT AWESOME LINK -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
         integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
         crossorigin="anonymous" referrerpolicy="no-referrer" />
     <!-- CUSTOM ADMIN CSS FILE -->
    <link rel="stylesheet" href="../css/admin_style.css">
 
 </head>
 <body>
 
 <?php include '../components/admin_header.php' ?>
 
 <!-- USER ACCOUNT STARTS -->
 
 <section class="accounts">
 
    <h1 class="heading">Users Account</h1>
 
    <div class="box-container">
 
    <?php
       $select_account = $conn->prepare("SELECT * FROM `users`");
       $select_account->execute();
       if($select_account->rowCount() > 0){
          while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
    ?>
    <div class="box">
       <p> User ID : <span><?= $fetch_accounts['id']; ?></span> </p>
       <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
       <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
    </div>
    <?php 
       }
    }else{
       echo '<p class="empty">NO ACCOUNTS AVAILABLE!</p>';
    }
    ?>
 
    </div>
 
 </section>
 
 <!-- USER ACCOUNTS END -->
 
 
 
 <!-- CUSTOM ADMIN JS FILE -->
 <script src="../js/admin_script.js"></script>
 
 </body>
 </html>