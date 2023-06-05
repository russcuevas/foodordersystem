<?php
// INCLUDING DATABASE
include '../components/connect.php';

// SESSIONS
session_start();
$riders_id = $_SESSION['riders_id'];
if (!isset($riders_id)) {
    header('location:rider_login.php');
    exit;
}

// FETCHING RIDERS
$select_rider = $conn->prepare("SELECT name FROM riders WHERE id = :riders_id");
$select_rider->bindParam(':riders_id', $riders_id);
$select_rider->execute();
$fetch_profile = $select_rider->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Dashboard</title>
    <!-- FAVICON LINK -->
    <link rel="shortcut icon" href="../favicon/rider/dashboard.png" type="image/x-icon">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM RIDER CSS FILE -->
    <link rel="stylesheet" href="../css/rider_style.css">
</head>
<body>

<!-- RIDER HEADER -->
<?php include '../components/rider_header.php' ?>
<!-- ENDS -->
    
<!-- RIDER DASHBOARD START -->
<section class="dashboard">
    <h1 class="heading">Rider Dashboard</h1>
    <div class="box-container">
        <div class="box">
            <h3>Welcome!</h3>
            <p><?= $fetch_profile['name']; ?></p>
            <a href="rider_update.php" class="btn">Update Profile</a>
        </div>

        <div class="box">
            <?php 
            $select_orders = $conn->prepare("SELECT * FROM orders WHERE payment_status = 'Pending' AND riders = '{$fetch_profile['name']}'");
            $select_orders->execute();
            $select_pending = $select_orders->rowCount();
            ?>
            <h3>Pending Orders</h3>
            <p><?= $select_pending; ?></p>
            <a href="rider_pendingorders.php" class="btn">View Pending Orders</a>
        </div>

        <div class="box">
            <?php
            $rider_name = $fetch_profile['name'];
            $total_price = 0;
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'Paid' AND riders = ?");
            $select_orders->execute([$rider_name]);
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
            $total_price += $fetch_orders['total_price'];
            }
            ?>
            <h3>Paid Orders</h3>
            <p>₱ <?= $total_price; ?></p>
            <a href="rider_paidorders.php" class="btn">View Paid Orders</a>
        </div>

    </div>
</section>
    
</body>
</html>


<!-- CUSTOM RIDER JS FILE -->
<script src="../js/rider_script.js"></script>