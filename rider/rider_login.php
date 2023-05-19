<?php 
include '../components/connect.php';
// SESSION
session_start();
if(isset($_SESSION['riders_id'])){
   header('location:rider_dashboard.php');
}

// LOGIN QUERIES
if(isset($_POST['submit'])){

   $username = $_POST['username'];
   $password = sha1($_POST['password']);

   if(empty($username) || empty($password)){
      echo 'PLEASE ENTER USERNAME AND PASSWORD FIRST!';
   }else{
      $select_rider = $conn->prepare("SELECT * FROM `riders` WHERE username = ? AND password = ?");
      $select_rider->execute([$username, $password]);

      if($select_rider->rowCount() > 0){
         $fetch_riders_id = $select_rider->fetch(PDO::FETCH_ASSOC);
         $_SESSION['riders_id'] = $fetch_riders_id['id'];
         header('location:rider_dashboard.php');
      }else{
         $message[] = 'INCORRECT USERNAME OR PASSWORD!';
      }
   }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/rider_style.css">
    <title>Riders Login</title>
</head>
<body>
<?php 
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
<section class="form-container">
    <form action="" method="POST">
        <h3 style="font-size : 35px;">Rider | Login</h3>
        <input type="text" class="box" name="username" placeholder ="Enter your username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" class="box" name="password" placeholder ="Enter your password" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" class="btn" name="submit" value="Login">
    </form>
</section>
</body>
</html>