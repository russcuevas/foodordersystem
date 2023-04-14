<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- ADMIN ACCOUNT STARTS -->

<section class="accounts">

   <h1 class="heading">Admins Account</h1>

   <div class="box-container">

   <div class="box">
      <p>REGISTER NEW ADMIN</p>
      <a href="register_admin.php" class="option-btn">Register</a>
   </div>

   <?php
      $select_account = $conn->prepare("SELECT * FROM `admin`");
      $select_account->execute();
      if($select_account->rowCount() > 0){
         while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <p> Admin ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <div class="flex-btn">
        <?php
            if($fetch_accounts['id'] == $admin_id){
               echo '<a href="update_profile.php" class="option-btn">update</a>';
            }
         ?>
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">No Accounts Available</p>';
   }
   ?>

   </div>

</section>

<!-- ADMIN ACCOUNT ENDS -->


<!-- CUSTOM ADMIN JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
