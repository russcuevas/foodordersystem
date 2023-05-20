<?php
// INCLUDING CONNECTION TO DATABASE
include 'components/connect.php';
// SESSIONS
session_start();

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    header('location:home.php');
 }else{
    $user_id = '';
 };

//  QUERY SELECTING THE USER TO FORGOT

$message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $select_user = $conn->prepare("SELECT password, resettoken FROM `users` WHERE email = ?");
    $select_user->execute([$email]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {

        $key = bin2hex(random_bytes(16));
        $password = $row['password'];

        $update_user = $conn->prepare("UPDATE `users` SET password = ?, resettoken = ? WHERE email = ?");
        $update_user->execute([$password, $key, $email]);


        $reset_link = "http://localhost/foodordersystem/reset_password.php?key=" . $key;

        $smtp_host = 'localhost';
        $smtp_port = 1025;

        $to = $email;
        $subject = 'Password Reset';
        $message = 'A password reset link has been sent to your email address. Click the link below to reset your password:' . "\r\n" . $reset_link;
        $headers = 'From: russarchiefoodorder@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        ini_set('SMTP', $smtp_host);
        ini_set('smtp_port', $smtp_port);

        if (mail($to, $subject, $message, $headers)) {
            $message = 'A password reset link has been sent to your email address. <br> <a style="color : red; cursor : pointer; text-decoration: underline;" href="http://localhost:8025/">Click here to check your inbox.</a>';
        } else {
            $message = 'Error while sending a reset link password, please try again.';
        }
    } else {
        $message = 'The email address is not registered.';
    }
}
?>

<!-- FORGOT PASSWORD PAGE -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/forgot_password.css">
    <!-- LINK IN FAVICON -->
    <link rel="shortcut icon" href="favicon/forgot-password.png" type="image/x-icon">
    <title>Forgot Password</title>
</head>
<body>
<!-- FORGOT PASSWORD STARTS -->
<section class="forgot-password">
   <form action="" method="POST">
      <h3>Forgot Password</h3>
        <?php if (!empty($message)) {
            echo "<p style='color: black;'>$message</p>";
        } ?>
        <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Reset Password" name="submit" class="btn">
    </form>
</section>
<!-- FORGOT PASSWORD ENDS -->

<!-- FOR LOADING -->
<div class="loading">
    <img src="images/email.gif" alt="">
</div>
<!-- END LOADING -->

<!-- CUSTOM JS LINK -->
<script src="js/script.js"></script>
<script>
    // FOR LOADING PAGE
    function loading() {
    document.querySelector('.loading').style.display = 'none';
    }

    function fadeOut() {
    setInterval(loading, 1000);
    }

    window.onload = fadeOut;
</script>

</body>
</html>

