<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CAN'T GO TO DIRECT PAGE
session_start();
$riders_id = $_SESSION['riders_id'];
if (!isset($riders_id)) {
    header('location:riders_login.php');
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
        if ($payment_status === 'paid') {
            $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
            $update_status->execute([$payment_status, $order_id]);
            $update_order = $conn->prepare("UPDATE `orders` SET order_status = 'paid' WHERE id = ?");
            $update_order->execute([$order_id]);
        } else {
            $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
            $update_status->execute([$payment_status, $order_id]);
            header('location: rider_pendingorders.php');
        }
    } else {
        echo 'Please choose again!';
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
</head>
<body>

<!--  RIDER HEADER -->
<?php include '../components/rider_header.php' ?>
<!-- ENDS -->

<section class="placed-orders">
   <h1 class="heading">Pending Orders </h1>
   <div class="box-container">
      <!-- SELECTING/FETCHING ORDERS QUERY -->
      <?php foreach ($orders as $order) {
          if ($order['payment_status'] === 'Pending') {
              $isHidden = ($order['riders'] !== $rider_name) ? 'hidden' : '';
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
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>
                    </select>
                    <div class="flex-btn">
                        <input type="submit" value="update" class="btn" name="update_payment">
                    </div>
                </form>
            </div>
      <?php
          }
      } ?>
   </div>
</section>

    
</body>
</html>

<!-- CUSTOM RIDER JS FILE -->
<script src="../js/rider_script.js"></script>
