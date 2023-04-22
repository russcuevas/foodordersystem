<?php

// INCLUDING DATABASE CONNECTION
include 'components/connect.php';

// SESSION START
session_start();

// IF THE USER IS LOGIN
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
// ELSE IT WILL SHOW
}else{
   $user_id = '';
   echo "<div style='background-color: #f8d7da; color: #721c24; padding: 10px; font-size:25px; text-align:center;'> <a href='login.php'>Login </a> first to see your receipt!</div>";
};

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Customer Receipt</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- CSS LINK  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- CUSTOMER RECEIPT STARTS -->
<section class="orders">
   <h1 class="title">Customer Receipt</h1>
   <a href="home.php" class="back-btn">Go Back</a>
        <div class="box-container">
        

        <?php
        if($user_id == ''){
        //  echo '<p class="empty"></p>';
        }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
        ?>
        <div class="box">
            <p>Date Order : <span><?= $fetch_orders['placed_on']; ?></span></p>
            <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
            <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
            <p>Number : <span><?= $fetch_orders['number']; ?></span></p>
            <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
            <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
            <p>Your orders : <span><?= $fetch_orders['total_products']; ?></span></p>
            <p>Total price : <span>₱<?= $fetch_orders['total_price']; ?></span></p>
            <p> Payment status : <span style="color:<?php if($fetch_orders['payment_status'] == 'pending'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
            <button class="print-btn" onclick="window.print()">Print Receipt</button>
        </div>
        <?php
            }
            }else{
            echo '<p class="empty">No orders placed yet!</p>';
                }
            }
        ?>
    </div>
</section>
<!-- CUSTOMER RECEIPT END -->

</body>
</head>