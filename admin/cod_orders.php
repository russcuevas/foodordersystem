<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// UPDATE STATUS QUERY
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
            $message[] = 'Payment status updated!';
        }
    } else {
        $message[] = 'Please choose again!';
    }
}

// DELETE STATUS QUERY
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
    $delete_order->execute([$delete_id]);
    header('location:cod_orders.php');
}

?>

<!-- COD ORDERS PAGE -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COD Orders</title>
    <!-- FAVICON LINK -->
    <link rel="shortcut icon" href="../favicon/rider/delivery.png" type="image/x-icon">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
    <link rel="stylesheet" href="../css/admin_style.css">

</head>

<body>

    <!-- INCLUDING HEADER -->
    <?php include '../components/admin_header.php'?>

    <!-- COD ORDERS STARTS -->
    <section class="placed-orders">
        <h1 class="heading">COD Orders</h1>
        <div class="box-container">
            <!-- SELECTING/FETCHING COD ORDERS QUERY -->
            <?php
            $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE method = 'CASH ON DELIVERY'");
            $select_orders->execute();
            if ($select_orders->rowCount() > 0) {
                while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
                    $total_price = $fetch_orders['total_price'];
                    ?>
                    <div class="box">
                        <p>User ID: <span><?=$fetch_orders['user_id'];?></span></p>
                        <p>Date ordered: <span><?=$fetch_orders['placed_on'];?></span></p>
                        <p>Name: <span><?=$fetch_orders['name'];?></span></p>
                        <p>Email: <span><?=$fetch_orders['email'];?></span></p>
                        <p>Number: <span><?=$fetch_orders['number'];?></span></p>
                        <p>Address: <span><?=$fetch_orders['address'];?></span></p>
                        <p>Food ordered: <span><?=$fetch_orders['total_products'];?></span></p>
                        <p>Total price: <span>₱<?=$total_price;?></span></p>
                        <p>Payment method: <span><?=$fetch_orders['method'];?></span></p>
                        <p>Delivery Rider: <span><?=$fetch_orders['riders']; ?></span></p>
                        <form action="" method="POST">
                            <input type="hidden" name="order_id" value="<?=$fetch_orders['id'];?>">
                            <select name="payment_status" class="drop-down">
                                <option value="" selected disabled><?=$fetch_orders['payment_status'];?></option>
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                            <div class="flex-btn">
                                <input type="submit" value="Update" class="btn" name="update_payment">
                                <a href="cod_orders.php?delete=<?=$fetch_orders['id'];?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this order?');">Delete</a>
                            </div>
                        </form>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="empty">No COD orders found.</p>';
            }
            ?>
        </div>
    </section>
    <!-- COD ORDERS END -->

    <!-- CUSTOM ADMIN JS FILE -->
    <script src="../js/admin_script.js"></script>

</body>

</html>
