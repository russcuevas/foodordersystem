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

$stmt = $conn->prepare("SELECT id, name, number, email, address, method, total_products, total_price, payment_status, user_id, placed_on, riders FROM orders WHERE method = 'CASH ON DELIVERY' AND payment_status = 'Paid' AND riders IN (SELECT name FROM riders WHERE id = ?) ORDER BY placed_on DESC LIMIT 1");
$stmt->execute([$riders_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/rider_style.css">
    <title>Receipt Issue</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
    <link rel="stylesheet" href="../css/rider_style.css">
    <style>
        .print-btn {
            background-color: orange;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        .print-btn:hover {
            background-color: #231F20;
            cursor: pointer;
        }

        /* Hide header and footer when printing */
        @media print {
            body{
                background-color: #fff;
                margin-top: 190px;
            }
            header, footer {
                display: none;
            }
            .print-btn{
                display: none;
            }
            p, span {
                font-size: 18px !important;
            }
            .box-container {
            max-width: none;
            margin: 0;
        }
        }
    </style>
</head>
<body>
    <!-- RIDER HEADER -->
    <?php include '../components/rider_header.php' ?>
    <!-- ENDS -->

    <section class="placed-orders">
    <h1 class="heading">Latest Paid Order</h1>
    <div class="box-container">
        <?php if ($order): ?>
            <!-- DISPLAY LATEST ORDER -->
            <div class="box">
                <p style="color: #E0163D;"> User ID: <span style="color: black;"><?php echo $order['user_id']; ?></span></p>
                <p style="color: #E0163D;"> Date ordered: <span style="color: black;"><?php echo $order['placed_on']; ?></span></p>
                <p style="color: #E0163D;"> Name: <span style="color: black;"><?php echo $order['name']; ?></span></p>
                <p style="color: #E0163D;"> Email: <span style="color: black;"><?php echo $order['email']; ?></span></p>
                <p style="color: #E0163D;"> Number: <span style="color: black;"><?php echo $order['number']; ?></span></p>
                <p style="color: #E0163D;"> Address: <span style="color: black;"><?php echo $order['address']; ?></span></p>
                <p style="color: #E0163D;"> Payment Method: <span style="color: green;"><?php echo $order['method']; ?></span></p>
                <p style="color: #E0163D;"> Food ordered: <span style="color: black;"><?php echo $order['total_products']; ?></span></p>
                <p style="color: #E0163D;"> Total price: <span style="color: black;">₱<?php echo $order['total_price']; ?></span></p>
                <p style="color: #E0163D;"> Delivery Rider: <span style="color: orange; text-decoration: underline; text-transform: uppercase;"><?php echo $rider_name; ?></span></p>
                <p style="color: #E0163D;"> Payment status: <span style="color: green;">Paid</span></p>
                <form action="" method="POST">
                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    <div class="flex-btn">
                        <button class="print-btn" onclick="window.print()">Print Receipt</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <p class="empty">No orders found yet!</p>
        <?php endif; ?>
    </div>
</section>


    
</body>
</html>


<!-- CUSTOM RIDER JS FILE -->
<script src="../js/rider_script.js"></script>
