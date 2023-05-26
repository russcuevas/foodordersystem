<?php
session_start();
require_once 'components/connect.php';

// Include Twilio PHP library
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;


// DEFAULT TIME ZONE IN OUR COUNTRY
date_default_timezone_set('Asia/Manila');

// SESSION CHECK IF USER IS LOGIN
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->execute([$_SESSION['user_id']]);
if ($select_cart->rowCount() == 0) {
    header('Location: checkout.php');
    exit();
}

// GET USER'S PROFILE
$user_id = $_SESSION['user_id'];
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->execute([$user_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

// GET CART ITEMS
$grand_total = 0;
$cart_items = [];
$select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
$select_cart->execute([$user_id]);
if ($select_cart->rowCount() > 0) {
    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
        $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ')';
        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
    }
}

// PREPARE DATA FOR TRANSACTION
$total_products = implode(', ', $cart_items);
$name = $fetch_profile['name'];
$number = $fetch_profile['number'];
$email = $fetch_profile['email'];
$address = $fetch_profile['address'];
$method = 'GCASH';
$payment_status = 'Pending';

// INSERTING DATA TO DATABASE
if (isset($_POST['submit'])) {
    $gcash_name = $_POST['gcash_name'];
    $gcash_num = $_POST['gcash_num'];
    $gcash_amount = $_POST['gcash_amount'];

    $errors = [];

    // Errors;
    if (empty($gcash_name)) {
        $errors[] = 'GCASH Name is required.';
    }

    if (empty($gcash_num)) {
        $errors[] = 'GCASH Number is required.';
    }

    if (empty($gcash_amount)) {
        $errors[] = 'Payment Amount is required.';
    } elseif ($gcash_amount < $grand_total) {
        $errors[] = 'Payment Amount must be greater than or equal to the Total Price!';
    }

    // IF NO ERROR
    if (empty($errors)) {
        $reference_number = uniqid();
        $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, total_products, total_price, name, number, email, address, method, gcash_name, gcash_num, gcash_amount, payment_status, change_amount, reference_number, placed_on) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $currentDateTime = new DateTime();
        $formattedDateTime = $currentDateTime->format('Y-m-d g:i:sA');

        $change_amount = $gcash_amount - $grand_total;
        $insert_order->execute([$user_id, $total_products, $grand_total, $name, $gcash_num, $email, $address, $method, $gcash_name, $gcash_num, $gcash_amount, $payment_status, $change_amount, $reference_number, $formattedDateTime]);

        $order_id = $conn->lastInsertId();
        $update_payment_status = $conn->prepare("UPDATE `orders` SET payment_status = 'Paid' WHERE id = ?");
        $update_payment_status->execute([$order_id]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        $_SESSION['payment_status'] = $payment_status;

        // Twilio configuration
        $twilioAccountSid = 'AC6edd5553e1464a89d9bfc78a69c96c39';
        $twilioAuthToken = '';
        $twilioPhoneNumber = '+13158955345';

        // Create a Twilio client
        $client = new Client($twilioAccountSid, $twilioAuthToken);

        // Retrieve the user's phone number
        $toPhoneNumber = '+63' . substr($number, 1);

        // Compose the message
        $messageBody = "Hello Mr. $name, you are successfully paid in your orders. Thank you for ordering! Total amount paid: ₱$gcash_amount - Russel Vincent C. Cuevas and Archie De Vera developers of food order system!";

        // Send the message
        $client->messages->create(
            $toPhoneNumber,
            [
                'from' => $twilioPhoneNumber,
                'body' => $messageBody
            ]
        );

        header('location: gcash_confirmation.php');
        exit();
    }
}

?>



<!-- GCASH PAGE -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initialization-scale=1.0">
    <title>GCASH Payment</title>
    <!-- FAVICON -->
    <link rel="shortcut icon" href="favicon/gcash.svg" type="image/x-icon">
    <!-- BOOTSTRAP LINK -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- STYLE CSS -->
    <link rel="stylesheet" href="css/gcash.css">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        .gcash-logo {
            display: block;
            margin: 0 auto;
            max-width: 200px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
        }
        .gcash-form label {
            font-weight: bold;
        }
        .gcash-form select,
        .gcash-form input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .gcash-form input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #0c80e0;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .gcash-form input[type="submit"]:hover {
            background-color: #085797;
        }
        .go-back-link {
            text-align: center;
            margin-top: 15px;
        }
        .go-back-link a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
	<header>
        <img src="images/gcash-logo.png" alt="">
        <h1 style="font-size: 20px; color: white;">PLEASE FILL UP THE FORM!</h1>
	</header>
	<main>
		<section class="gcash-form">
			<form method="POST">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger p-0">
                        <ul class="m-1">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <label class="mt-2" for="">GCASH Name : </label><br>
                <select name="gcash_name" id="">
                    <option value="">SELECT GCASH NAME : </option>
                    <option value="Russ A. Chie">Russ A. Chie</option>
                    <!-- <option value="Archie De Vera">Archie De Vera</option> -->
                </select><br>
                <label for="">GCASH Number : </label><br>
                <select name="gcash_num" id="">
                    <option value="">SELECT GCASH NUMBER : </option>
                    <option value="09495748302">09495748302</option>
                    <!-- <option value="09123456789">09123456789</option> -->
                </select><br>
                <label for="gcash_amount">GCASH Payment Amount :</label><br>
                <input type="text" id="gcash_amount" name="gcash_amount" value="<?php echo isset($_POST['gcash_amount']) ? $_POST['gcash_amount'] : '' ?>" 
                oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 6); if (parseInt(this.value) > 100000) { this.value = '100000'; }" 
                maxlength="6" placeholder="Please enter your payment here.."><br>
                
                <p class="bg-danger" style="color: white; font-style: bold; font-size: 30px; text-align: center;">TO PAY: <span>₱<?php echo $grand_total; ?></span></p>
                    <input type="submit" name="submit" value="SUBMIT PAYMENT">
                <div>
                <a href="checkout.php" class="btn btn-danger p-2 mt-2 text-white">GO BACK</a>
                </div>
			</form>
		</section>
	</main>


    <footer>
        <p style="margin-top: 20px;">&copy; Russel Vincent Cuevas 2023 GCASH-CLONE</p>
    </footer>

<div class="loading">
    <img src="images/gcash.gif" alt="">
</div>

<script>
// FOR LOADING PAGE
function loading() {
   document.querySelector('.loading').style.display = 'none';
}

function fadeOut() {
   setInterval(loading, 2000);
}

window.onload = fadeOut;
</script>
</body>
</html>

