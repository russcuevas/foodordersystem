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
        if (isset($_POST['submit'])) {
            $password = $_POST['password'];

            $update_password = $conn->prepare("UPDATE `users` SET password = ?, resettoken = NULL WHERE resettoken = ?");
            $update_password->execute([sha1($password), $key]);

            $message = 'Your password has been successfully reset. You can now login with your new password.';
        }
    } else {
        $message = 'Invalid reset key.';
    }
} else {
    $message = 'Invalid reset key.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Reset Password</title>
</head>
<body>
   <h3>Reset Password</h3>
   <?php if (!empty($message)) { ?>
      <p><?php echo $message; ?></p>
   <?php } ?>
   <?php if ($select_user->rowCount() > 0) { ?>
      <form action="" method="post">
         <input type="password" name="password" required placeholder="Enter your new password" maxlength="50">
         <input type="submit" name="submit" value="Reset Password">
      </form>
   <?php } ?>
</body>
</html>
