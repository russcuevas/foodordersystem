<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

// UPDATE PROFILE QUERY

if(isset($_POST['submit'])){
   $old_pass = sha1($_POST['old_pass']);
   $new_pass = sha1($_POST['new_pass']);
   $confirm_pass = sha1($_POST['confirm_pass']);

   // ICHECHECK KUNG ANG OLD PASSWORD AY MERON SA DATABASE
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $prev_pass = $select_old_pass->fetchColumn();

   if($old_pass != $prev_pass){
       $message[] = '• Old password not matched!';
   } elseif($new_pass != $confirm_pass){
       $message[] = '• Confirm password not matched!';
   } elseif(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $_POST['new_pass'])) {
       $message[] = '• Password must be at least 12 characters long <br> 
                     • Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character!';
   } else {
       // UPDATE PASSWORD IN THE DATABASE
       $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
       $update_pass->execute([$confirm_pass, $admin_id]);
       $message[] = '• PASSWORD SUCCESSFULLY UPDATED!';
   }
}
 
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Profile Update</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- ADMIN UPDATE PROFILE STARTS -->

<section class="form-container">

   <form action="" method="POST">
      <h3>Update Profile</h3>
      <input type="password" name="old_pass" required placeholder="Enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" required placeholder="Enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" required placeholder="Confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update" name="submit" class="btn">
   </form>

</section>

<!-- ADMIN UPDATE PROFILE END -->


<!-- CUSTOM ADMIN JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>