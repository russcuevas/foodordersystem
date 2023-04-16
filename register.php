<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
   header('location:home.php');
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

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
    
    if(empty($name)){
        $message[] = '• Name is required!';
    }
    elseif(!$uppercase || !$lowercase || !$passnum || !$specialChars || strlen($pass) < 12) {
        $message[] = '• Password must contain at least 12 characters, including uppercase letters, lowercase letters, and special characters.';
    }
    else{
        $pass = sha1($pass);
        $cpass = sha1($cpass);
     
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
        $select_user->execute([$email, $number]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
     
        if($select_user->rowCount() > 0){
           $message[] = '• Email or number already exists!';
        }
        else{
           if($pass != $cpass){
              $message[] = '• Confirm password not matched!';
           }
           else{
              $insert_user = $conn->prepare("INSERT INTO `users`(name, email, number, address, password) VALUES(?,?,?,?,?)");
              $insert_user->execute([$name, $email, $number, $address, $cpass]);
              $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
              $select_user->execute([$email, $pass]);
              $row = $select_user->fetch(PDO::FETCH_ASSOC);
              if($select_user->rowCount() > 0){
                $_SESSION['user_id'] = $row['id'];
                header('location:home.php');
              }
           }
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
   <title>Register Form</title>

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
    <input type="text" name="name" placeholder="Enter your name" class="box">
    <input type="email" name="email" required placeholder="Enter your email" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="11">
    <input type="text" name="address" required placeholder="Enter your address" class="box">
    <input type="password" name="pass" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="password" name="cpass" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
    <input type="submit" value="register now" name="submit" class="btn">
    <p>Already have an account? <a href="login.php">login now</a></p>
</form

</section>
<!-- REGISTER FORM END -->

<!-- INCLUDING FOOTER -->
<?php include 'components/footer.php';?>
<!-- FOOTER ENDS -->

<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>

</body>
</html>