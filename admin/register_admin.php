<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

// REGISTER ADMIN QUERIES
if (isset($_POST['submit'])){
    $name = $_POST['name'];
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    $password_error = false; // INDICATION

    if (empty($pass)) {
        $message[] = '• Password is required!';
        $password_error = true;
    } elseif (strlen($pass) < 12 || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $pass)) {
        $message[] = '• Password must be at least 12 characters long <br> 
                      • Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character!';
        $password_error = true;
    }

    if (empty($cpass)) {
        $message[] = '• Confirm password is required!';
        $password_error = true;
    } elseif ($pass != $cpass) {
        $message[] = '• Confirm password not matched!';
        $password_error = true;
    }

    if (empty($admin) && !$password_error) { // CHECK THE PASSWORD BEFORE EXECUTING THE QUERY
        $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
        $select_admin->execute([$name]);
        $cpass = sha1($cpass);

        if ($select_admin->rowCount() > 0) {
            $message[] = '• Username already exists!';
        } else {
            $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?,?)");
            $insert_admin->execute([$name, $cpass]);
            $message[] = '• NEW ADMIN REGISTERED!';
        }
    }
}

?>

 <!-- REGISTER PAGE -->

 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<!-- INCLUDING HEADER -->

<?php include '../components/admin_header.php'; ?>

<!-- REGISTER ADMIN STARTS -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Register</h3>
      <input type="text" name="name" placeholder="Enter your username" class="box" required oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" name="submit" class="btn">
   </form>

</section>

<!-- REGISTER ADMIN ENDS -->


<!-- CUSTOM ADMIN JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>