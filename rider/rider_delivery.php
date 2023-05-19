<?php
// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';
require __DIR__ . '/../vendor/autoload.php';
use Twilio\Rest\Client;

$sid = "AC1b234dbe251c725f4e03ce448dee6e65";
$token = "e1b33d4d5a9db5dfe97025d4ca9ae3bc";
$client = new Client($sid, $token);

// SESSION IF NOT LOGIN YOU CAN'T GO TO DIRECT PAGE
session_start();
$riders_id = $_SESSION['riders_id'];
if (!isset($riders_id)) {
    header('location:riders_login.php');
    exit; // Stop further execution
}

// Get the rider's name based on the ID
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
                'from' => '+12525168668',
                'body' => $messageContent
            ]
        );
        echo 'Message sent successfully! SID: ' . $message->sid;
    } else {
        echo 'Invalid order selected.';
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
</head>
<body>
    <form action="" method="POST">
        <h1>Rider Dashboard</h1>
        <label for="name">Receiver</label>
        <select name="name" id="name">
            <?php foreach ($orders as $order) : ?>
                <option value="<?php echo $order['id']; ?>"><?php echo $order['name']; ?></option>
            <?php endforeach; ?>
        </select><br>
        <label for="message">Message:</label><br>
        <textarea name="message" id="message" cols="30" rows="10"></textarea><br>
        <input type="submit" name="submit" value="Send">
    </form>
</body>
</html>
