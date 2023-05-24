<?php 

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION
session_start();
if(isset($_SESSION['admin_id'])){
   header('location:dashboard.php');
}

// LOGIN QUERIES
if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $pass = sha1($_POST['pass']);

   if(empty($name) || empty($pass)){
      $message[] = 'PLEASE ENTER USERNAME AND PASSWORD FIRST!';
   }else{
      $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
      $select_admin->execute([$name, $pass]);

      if($select_admin->rowCount() > 0){
         $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
         $_SESSION['admin_id'] = $fetch_admin_id['id'];
         header('location:dashboard.php');
      }else{
         $message[] = 'INCORRECT USERNAME OR PASSWORD!';
      }
   }

}

?>


<!-- LOGIN PAGE -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- FAVICON LINK -->
    <link rel="shortcut icon" href="../favicon/rider/login.png" type="image/x-icon">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- CUSTOM ADMIN CSS -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php 

// MESSAGE SA TAAS PARA KITA NG USER KUNG NAG LOGIN BA OR LOGIN FAILED

if(isset($message)){
    foreach($message as $message){
       echo '
       <div class="message">
          <span>'.$message.'</span>
          <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
       </div>
       ';
    }
 }
?>

<!-- ADMIN LOGIN STARTS -->

<section class="form-container">
    <form action="" method="POST">
        <h3 style="font-size : 35px;">Admin | Login</h3>
        <h4 style="color: red; margin-top: 15px; font-size: 12px;">"GRANT ACCESS AUTHORIZE BY ADMIN ONLY!"</h4>
        <input type="text" class="box" name="name" placeholder ="Enter your username" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" class="box" name="pass" placeholder ="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" class="btn" name="submit" value="Login">
    </form>
</section>

<!-- ADMIN LOGIN ENDS -->

    
</body>
</html>