<?php
// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// INCLUDING COMPOSER FOR TEXTING MESSAGE
require __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

$sid = "AC6edd5553e1464a89d9bfc78a69c96c39";
$token = "6243722fca01728ce8be302c288e051b";
$client = new Client($sid, $token);

// SESSION IF NOT LOGIN YOU CAN'T GO TO DIRECT PAGE
session_start();
$riders_id = $_SESSION['riders_id'];
if (!isset($riders_id)) {
    header('location:rider_login.php');
    exit;
}

$stmt = $conn->prepare("SELECT name FROM riders WHERE id = ?");
$stmt->execute([$riders_id]);
$rider = $stmt->fetch(PDO::FETCH_ASSOC);

$rider_name = $rider['name'];

$stmt = $conn->prepare("SELECT id, name, number FROM orders WHERE method = 'CASH ON DELIVERY' AND payment_status = 'Pending' AND riders IN (SELECT name FROM riders WHERE id = ?)");
$stmt->execute([$riders_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedOrderId = $_POST['name'];
    $selectedMessage = $_POST['message'];

    $phoneNumber = null;
    foreach ($orders as $order) {
        if ($order['id'] == $selectedOrderId) {
            $phoneNumber = $order['number'];
            break;
        }
    }

    if ($phoneNumber) {
        $formattedPhoneNumber = '+63' . substr($phoneNumber, 1);
        $messageContent = $selectedMessage . ' - Delivery Rider: ' . $rider_name . ' - Thankyou for your patience! ' ;
        $message = $client->messages->create(
            $formattedPhoneNumber,
            [
                'from' => '+13158955345',
                'body' => $messageContent
            ]
        );
        $success = 'Message sent successfully! ID: ' . $message->sid;
    } else {
        $error = 'Invalid order selected.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Delivery</title>
    <link rel="shortcut icon" href="../favicon/rider/delivery.png" type="image/x-icon">
    <!-- I USE INTERNAL CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h1 {
            text-align: center;
        }

        .message {
            background-color: #f1f0f0;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .message p {
            margin: 0;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        select, textarea, input[type="submit"] {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #E0163D;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c91437;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Rider Text Message</h1>
        <div class="message">
            <?php if (!empty($success)) : ?>
                <p>Sender : <span style="color: red;"><?php echo $rider_name; ?></span></p>
            <?php endif; ?>
        </div>
        <form action="" method="POST">
            <label for="name">Receiver:</label>
            <select name="name" id="name" required>
                <?php foreach ($orders as $order) : ?>
                    <option value="<?php echo $order['id']; ?>"><?php echo $order['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="message">Message:</label>
            <textarea name="message" id="message" cols="30" rows="10"></textarea>
            <input type="submit" name="submit" value="Send">
            <a href="rider_dashboard.php" style="text-decoration: none;">Go Back</a>
        </form>
        <?php if (!empty($success)) : ?>
            <p style="color: green;"><?php echo $success; ?></p>
        <?php endif; ?>
        <?php if (!empty($error)) : ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>

