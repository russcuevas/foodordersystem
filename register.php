<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
   header('location:home.php');
} else {
   $user_id = '';
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    
    // CHECK IF THE PASSWORD IS CONTAIN WITH THIS QUERY
    $uppercase = preg_match('@[A-Z]@', $pass);
    $lowercase = preg_match('@[a-z]@', $pass);
    $passnum = preg_match('@[0-9]@', $pass);
    $specialChars = preg_match('@[^\w]@', $pass);
    
    if (empty($name)){
        $message[] = '• Name is required!';
    }
    elseif (!$uppercase || !$lowercase || !$passnum || !$specialChars || strlen($pass) < 12) {
        $message[] = '• Password must contain at least 12 characters, including uppercase letters, lowercase letters, and special characters.';
    }
    else {
        $pass = sha1($pass);
        $cpass = sha1($cpass);
     
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
        $select_user->execute([$email, $number]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
     
        if ($select_user->rowCount() > 0){
           $message[] = '• Email or number already exists!';
        }
        else {
           if ($pass != $cpass) {
              $message[] = '• Confirm password not matched!';
           }
           else {
              $verificationCode = generateVerificationCode();

              $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, address, password, verification_code, status) VALUES(?,?,?,?,?,?,?)");
              $insert_user->execute([$name, $email, $number, $address, $cpass, $verificationCode, 0]);
              
              $verificationSent = sendVerificationEmail($email, $name, $verificationCode);
              
              if ($verificationSent) {
                $message[] = '• Your account registration is in pending verification check your email.';
               //  exit();
              } else {
                $message[] = '• Failed to send verification email. Please try again.';
              }
           }
        }
    }
}

/**
 * 
 *
 * @return string
 */
function generateVerificationCode() {
   $characters = '0123456789';
   $verificationCode = '';
   $length = 4;
   
   for ($i = 0; $i < $length; $i++) {
   $verificationCode .= $characters[rand(0, strlen($characters) - 1)];
   }
   
   return $verificationCode;
  }
  
/**
 *
 *
 * @param string
 * @param string
 * @return bool
 */
function sendVerificationEmail($email, $name, $verificationCode) {
    $mail = new PHPMailer(true);
    
    try {
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
        $mail->isHTML(true);
        $mail->Subject = 'Account Verification';
        
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
                        <h1 style="color: white;">Account Verification</h1>
                    </div>
                    <div class="content">
                        <p>Dear <span style="font-weight: bold;">' . $name . ',</span></p>
                        <p>Please click the following link to activate your account:</p>
                        <p><a class="button" style="color: white;" href="https://localhost/foodordersystem/verification.php?code=' . $verificationCode . '">Verify Account</a></p>
                        <p>If the button above does not work, you can copy and paste the following URL into your browser:</p>
                        <p>https://localhost/foodordersystem/verification.php?code=' . $verificationCode . '</p>
                        
                        <p class="footer" style="text-align: center;">© to Mr. Russel Vincent C. Cuevas &amp; Archie De Vera, developers of the FOOD ORDER SYSTEM<br>
                        This message was sent to ' . $email . '.<br>
                        To help keep your account secure, please don\'t forward this email.</p>
                    </div>
                </div>
            </body>
            </html>
        ';
        
        $mail->send();
        
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registration | Page</title>
   <!-- LINK IN FAVICON -->
   <link rel="shortcut icon" href="favicon/user.png" type="image/x-icon">
   <!-- FONT AWESOME LINK -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
   integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
   crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- CSS LINK  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<!-- INCLUDING HEADER -->

<?php include 'components/user_header.php';?>

<!-- HEADER END -->


<!-- REGISTER FORM START -->
<section class="form-container">

<form action="register.php" method="post">
    <h3>Register to order!</h3>
    <input type="text" name="name" placeholder="Enter your name" class="box" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
    <input type="email" name="email" required placeholder="Enter your email" class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
    <h6 style="text-align: start; color: #6D5D6E; font-size: 13px;">Note : <span style="color: red; font-size:12px;">"Use gmail that is valid"</span></h6>
    <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="11" value="<?php echo isset($_POST['number']) ? $_POST['number'] : ''; ?>">
    <input type="text" name="address" required placeholder="Enter your address" class="box" value="<?php echo isset($_POST['address']) ? $_POST['address'] : ''; ?>">
    <input type="password" name="pass" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="password" name="cpass" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <h6 style="text-align: start; color: #6D5D6E; font-size: 13px;">Note : <span style="color: red; font-size:12px;">"Don't give your password to anyone else"</span></h6>
    <input type="submit" value="register now" name="submit" class="btn">
    <p>Already have an account? <a href="login.php">login now</a></p>
</form>
<div class="pwgenerator" style="border: 2px solid black; max-width:600px; margin: 0 auto; margin-top:20px; padding:20px; text-align: center;">
    <h2 style="margin-bottom: 2px; color: red; font-size: 20px;">"PASSWORD GENERATOR"</h2>
    <div class="input-group">
        <input type="text" name="genpass" placeholder="Click the button generate" class="generate" readonly>
        <button type="button" id="generatePassword">Generate Password</button>
    </div>
</div>

</section>
<!-- REGISTER FORM END -->

<!-- CUSTOM JS FILE -->
<script>
//  PASSWORD GENERATOR
const passwordInput = document.querySelector('input[name="genpass"]');
const generatePasswordBtn = document.querySelector('#generatePassword');

// Create an array of characters that can be used in the password
const uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
const lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
const specialCharacters = '!@#$%^&*()_+-={}[];\',./<>?';
const numbers = '0123456789';
const allCharacters = uppercaseLetters + lowercaseLetters + specialCharacters + numbers;

// Function to generate a random password
function generatePassword() {
   let password = '';
   // Generate one character from each category
   password += uppercaseLetters.charAt(Math.floor(Math.random() * uppercaseLetters.length));
   password += lowercaseLetters.charAt(Math.floor(Math.random() * lowercaseLetters.length));
   password += specialCharacters.charAt(Math.floor(Math.random() * specialCharacters.length));
   password += numbers.charAt(Math.floor(Math.random() * numbers.length));
   // Generate the remaining characters randomly
   for (let i = 0; i < 8; i++) {
      password += allCharacters.charAt(Math.floor(Math.random() * allCharacters.length));
   }
   // Shuffle the password to make it more random
   password = shuffle(password);
   passwordInput.value = password;
}

// Function to shuffle a string
function shuffle(string) {
   let arr = string.split('');
   for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
   }
   return arr.join('');
}

// Add an event listener to the generate password button
generatePasswordBtn.addEventListener('click', generatePassword);
</script>

<script src="js/script.js"></script>

</body>
</html>
