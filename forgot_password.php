<?php
// INCLUDING CONNECTION TO DATABASE
include 'components/connect.php';
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


// SESSIONS
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    header('location:home.php');
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $user['id'];
        $fullname = $user['name'];
        $status = $user['status'];

        if ($status == 1) {
            $resetToken = bin2hex(random_bytes(32));

            $timezone = new DateTimeZone('Asia/Manila');
            $currentDateTime = new DateTime('now', $timezone);
            $expirationTime = $currentDateTime->modify('+30 minutes')->format('Y-m-d H:i:s');

            $updateQuery = "UPDATE users SET resettoken = :resettoken, resetexpires = :resetexpires WHERE id = :id";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bindParam(':resettoken', $resetToken);
            $stmt->bindParam(':resetexpires', $expirationTime);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->SMTPDebug = 0;
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = 587;
            $mail->Username = 'russelarchiefoodorder@gmail.com';
            $mail->Password = 'shikytxtwosptwao';

            $mail->setFrom('russelarchiefoodorder@gmail.com', 'FOOD ORDER SYSTEM');
            $mail->addAddress($email, 'Recipient Name');

            $mail->Subject = 'Password Reset';

            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                    }
                    
                    .container {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                    }
                    
                    .header {
                        background-color: #E0163D;
                        padding: 10px;
                        text-align: center;
                    }
                    
                    .content {
                        padding: 20px;
                        background-color: #ffffff;
                    }
                    
                    .button {
                        display: inline-block;
                        margin-top: 20px;
                        background-color: #E0163D;
                        padding: 10px 20px;
                        text-decoration: none;
                        border-radius: 4px;
                    }
    
                    .footer {
                        margin-top: 20px;
                        font-size: 12px;
                        color: #808080;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1 style="color: white;">Password Reset</h1>
                    </div>
                    <div class="content">
                        <p>Dear <span style="font-weight: bold;">Mr. ' . $fullname . ',</span></p>
                        <p>Please click the following link to reset your password:</p>
                        <p><a class="button" style="color: white;" href="https://localhost/foodordersystem/reset_password.php?key=' . $resetToken . '">Click to reset</a></p>
                        <p>If the button above does not work, you can copy and paste the following URL into your browser:</p>
                        <p>https://localhost/foodordersystem/reset_password.php?key=' . $resetToken . '</p>
                        
                        <p class="footer" style="text-align: center;">© to Mr. Russel Vincent C. Cuevas &amp; Archie De Vera, developers of the FOOD ORDER SYSTEM<br>
                        This message was sent to ' . $email . '.<br>
                        To help keep your account secure, please don\'t forward this email.</p>
                    </div>
                </div>
            </body>
            </html>
            ';
    
            $mail->AltBody = 'Dear User, please copy and paste the following URL into your browser to reset your password: https://localhost/foodordersystem/reset_password.php?key=' . $resetToken;

            if ($mail->send()) {
                $message = '<p style="color: green;">Password reset instructions have been sent to your email. <a style="color: red; text-decoration: underline;" href="https://accounts.google.com/v3/signin/identifier?dsh=S638507798%3A1684940207096491&continue=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&emr=1&followup=https%3A%2F%2Fmail.google.com%2Fmail%2Fu%2F0%2F&ifkv=Af_xneG2m22dPmicbTL511Q3gkDlfMt2FuSXQfeubYo3YwUf2vpjHJ8qh8g0Nbupis_rzQZGDdcn&osid=1&passive=1209600&service=mail&flowName=GlifWebSignIn&flowEntry=ServiceLogin" target="_blank">please check here</a>.</p>';
            } else {
                $message = '<p style="color: red;">Unable to send password reset instructions. Please try again later.</p>';
            }
        } else {
            $message = '<p style="color: red;">Email is not registered.</p>';
        }
    } else {
        $message = '<p style="color: red;">Email is not registered.</p>';
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
    <a href="login.php" style="display: inline-block; margin-top: 10px;">Go back</a>
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


