<?php
include 'components/connect.php';
session_start();

$message = '';

if (isset($_GET['key'])) {
    $key = $_GET['key'];

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE resettoken = ?");
    $select_user->execute([$key]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        // Check if the reset token has expired
        $resetExpires = $row['resetexpires'];
        $currentDateTime = date('Y-m-d H:i:s');
        if ($resetExpires < $currentDateTime) {
            $message = '<p style="color : red">Reset token has expired. Please try to reset your password again.</p>';
        } else {
            if (isset($_POST['submit'])) {
                $password = $_POST['password'];
                $cpass = $_POST['cpass'];

                $uppercase = preg_match('@[A-Z]@', $password);
                $lowercase = preg_match('@[a-z]@', $password);
                $passnum = preg_match('@[0-9]@', $password);
                $specialChars = preg_match('@[^\w]@', $password);

                if (!$uppercase || !$lowercase || !$passnum || !$specialChars || strlen($password) < 12) {
                    $message = '<p style="color: red;">Password must contain at least 12 characters, including uppercase letters, lowercase letters, and special characters.</p>';
                } elseif ($password === $cpass) {
                    $update_password = $conn->prepare("UPDATE `users` SET password = ?, resettoken = NULL, resetexpires = NULL WHERE resettoken = ?");
                    $update_password->execute([sha1($password), $key]);
            
                    $message = '<p style="color: green;">Your password has been successfully reset. <a style="color : red; cursor : pointer; text-decoration: underline; text-align:" href="login.php">click here to login.</a></p>';
                } else {
                    $message = '<p style="color: red;">Passwords do not match.</p>';
                }
            }
        }
    } else {
        $message = 'Invalid reset key. <br> <a href="login.php">Go Back</p>';
    }
} else {
    header('location: forgot_password.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- LINK IN FAVICON -->
    <link rel="shortcut icon" href="favicon/password.png" type="image/x-icon">
    <!-- STYLE CSS -->
    <link rel="stylesheet" href="css/forgot_password.css">
    <title>Reset Password</title>
</head>
<body>
   <div class="forgot-password">
      <h3>Reset Password</h3>
      <?php if (!empty($message)) { ?>
         <p><?php echo $message; ?></p>
      <?php } ?>
      <?php if ($select_user->rowCount() > 0 && empty($message)) { ?>
         <form action="" method="post">
            <input type="password" name="password" required placeholder="Enter your new password" maxlength="50">
            <input type="password" name="cpass" required placeholder="Confirm your new password" maxlength="50">
            <input type="submit" name="submit" value="Reset Password">
         </form>
      <?php } elseif ($select_user->rowCount() > 0) { ?>
         <form action="" method="post">
            <input type="password" name="password" required placeholder="Enter your new password" maxlength="50">
            <input type="password" name="cpass" required placeholder="Confirm your new password" maxlength="50">
            <input type="submit" name="submit" value="Reset Password">
         </form>
      <?php } ?>
   </div>

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
