<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CAN'T GO TO DIRECT PAGE
session_start();
$riders_id = $_SESSION['riders_id'];
if (!isset($riders_id)) {
    header('location:rider_login.php');
}

// Get the rider's name based on the ID
$stmt = $conn->prepare("SELECT name FROM riders WHERE id = ?");
$stmt->execute([$riders_id]);
$rider = $stmt->fetch(PDO::FETCH_ASSOC);

$rider_name = $rider['name'];

$stmt = $conn->prepare("SELECT id, name, number, email, address, total_products, total_price, payment_status, user_id, placed_on, riders FROM orders WHERE method = 'CASH ON DELIVERY' AND riders IN (SELECT name FROM riders WHERE id = ?)");
$stmt->execute([$riders_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['update_payment'])) {
   if (isset($_POST['order_id']) && isset($_POST['payment_status'])) {
       $order_id = $_POST['order_id'];
       $payment_status = $_POST['payment_status'];
       
       if ($payment_status === 'Paid') {
           date_default_timezone_set('Asia/Manila');
           $formattedDateTime = date('Y-m-d g:i:sA');
           
           $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ?, placed_on = ? WHERE id = ?");
           $update_status->execute([$payment_status, $formattedDateTime, $order_id]);
           
           $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Paid' WHERE id = ?");
           $update_order->execute([$order_id]);
           header('location: rider_pendingorders.php');
       }
   } else {
       $message[] = 'Please choose again!';
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/rider_style.css">
    <title>Riders Pending Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<!--  RIDER HEADER -->
<?php include '../components/rider_header.php' ?>
<!-- ENDS -->

<section class="placed-orders">
   <h1 style="color: red;" class="heading">Pending Orders</h1>
   <div class="box-container">
      <!-- SELECTING/FETCHING ORDERS QUERY -->
      <?php 
      $hasPendingOrders = false;
      foreach ($orders as $order) {
         if ($order['payment_status'] === 'Pending') {
            $isHidden = ($order['riders'] !== $rider_name) ? 'hidden' : '';
            $hasPendingOrders = true;
      ?>
            <div class="box" <?php echo $isHidden; ?>>
               <p> User ID: <span><?php echo $order['user_id']; ?></span> </p>
               <p> Date ordered: <span><?php echo $order['placed_on']; ?></span> </p>
               <p> Name: <span><?php echo $order['name']; ?></span> </p>
               <p> Email: <span><?php echo $order['email']; ?></span> </p>
               <p> Number: <span><?php echo $order['number']; ?></span> </p>
               <p> Address: <span><?php echo $order['address']; ?></span> </p>
               <p> Food ordered: <span><?php echo $order['total_products']; ?></span> </p>
               <p> Total price: <span>₱<?php echo $order['total_price']; ?></span> </p>
               <form action="" method="POST">
                  <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                  <select name="payment_status" class="drop-down">
                     <option value="" selected disabled><?php echo $order['payment_status']; ?></option>
                     <option value="Paid">Paid</option>
                  </select>
                  <div class="flex-btn">
                     <input type="submit" value="update" class="btn" name="update_payment">
                  </div>
               </form>
            </div>
      <?php
         }
      } 

      if (!$hasPendingOrders) {
         echo '<p class="empty">No pending orders!</p>';
      }
      ?>
   </div>
</section>

    
</body>
</html>

<!-- CUSTOM RIDER JS FILE -->
<script src="../js/rider_script.js"></script>
