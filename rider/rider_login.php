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
         echo 'INCORRECT USERNAME OR PASSWORD!';
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
    <title>Riders Login</title>
</head>
<body>
    <form action="" method="POST">
        <h1>Rider Login</h1>
        <label for="">Username : </label>
        <input type="text" name="username"><br>
        <label for="">Password : </label>
        <input type="password" name="password"> <br>
        <input type="submit" name="submit">
    </form>
</body>
</html>