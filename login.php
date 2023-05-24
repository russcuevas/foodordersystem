<?php

// INCLUDING DATABASE
include 'components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
// AND IF YOU NOT LOGOUT AND YOU TRY TO GO TO LOGIN PAGE YOU WILL BACK AT HOME PAGE
session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   header('location:home.php');
}else{
   $user_id = '';
};

// LOGIN QUERY
if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $pass = sha1($_POST['pass']);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = '• Incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login | Page</title>
   <!-- LINK IN FAVICON -->
   <link rel="shortcut icon" href="favicon/rider/login.png" type="image/x-icon">
   <!-- FONT AWESOME LINK -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   <!-- CSS LINK  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- HEADER SECTION START -->
<?php include 'components/user_header.php'; ?>
<!-- HEADER SECTION END -->

<!-- LOGIN FORM START -->
<section class="form-container">
   <form action="" method="post">
      <h3>Login!</h3>
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')"  value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <h6 style="text-align: start; color: #6D5D6E; font-size: 13px; margin-bottom: 5px;">Note : <span style="color: red; font-size:12px;">"Dont give your password to anyone else"</span></h6>
      <input type="submit" value="login now" name="submit" class="btn">
      <p>Don't have an account? <a href="register.php">register now!</a></p>
      <p><a href="forgot_password.php" style="color: red;">Forgot your password?</a></p>
   </form>
</section>

<!-- LOGIN FORM END -->






<!-- CUSTOM JS LINK  -->
<script src="js/script.js"></script>

</body>
</html>