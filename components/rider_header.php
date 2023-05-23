<?php

// MESSAGE SA TAAS NG NAVBAR PARA MAKITA NG Rider Dashboard KUNG NAG 
// AADD BA OR FAILED ANG PAG IINSERT PAGDEDELTE NG PRODUCT OR NG MGA USER DIN
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">

   <section class="flex">

      <a href="rider_dashboard.php" class="logo">Rider Dashboard</a>

      <nav class="navbar">
         <a href="rider_dashboard.php">Home</a>
         <a href="rider_delivery.php">Text Message</a>
         <a href="rider_pendingorders.php">Pending Orders</a>
         <a href="rider_paidorders.php">Paid Orders</a>
         <!-- <a href="users_accounts.php">Users</a>
         <a href="messages.php">Messages</a> -->
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

   <div class="profile">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `riders` WHERE id = ?");
         $select_profile->execute([$riders_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <img style="display: block; margin-left: auto; margin-right: auto; width: 100px; height: 100px; border-radius: 50%; margin-top: 10px;" src="../uploaded_img/<?= $fetch_profile['image']; ?>" alt="Rider's Profile Picture">
      <p><?= $fetch_profile['name']; ?></p>
      <a href="rider_update.php" class="btn">Update Profile</a>
      <a href="../components/rider_logout.php" class="delete-btn">Logout</a>
   </div>


   </section>

</header>