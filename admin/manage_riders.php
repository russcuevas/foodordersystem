<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

// ADD RIDERS QUERIES
if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $username = $_POST['username'];
   $password = $_POST['password'];
   $confirm_password = $_POST['confirm_password'];

   $uppercase = preg_match('@[A-Z]@', $password);
   $lowercase = preg_match('@[a-z]@', $password);
   $passnum = preg_match('@[0-9]@', $password);
   $specialChars = preg_match('@[^\w]@', $password);

   if (empty($name)) {
       $message[] = '• Name is required!';
   } elseif (!$uppercase || !$lowercase || !$passnum || !$specialChars || strlen($password) < 12) {
       $message[] = '• Password must contain at least 12 characters, including uppercase letters, lowercase letters, and special characters.';
   } elseif ($password !== $confirm_password) {
       $message[] = '• Password and Confirm Password do not match.';
   } else {
       $password = sha1($password);

       $image = $_FILES['image']['name'];
       $image_size = $_FILES['image']['size'];
       $image_tmp_name = $_FILES['image']['tmp_name'];
       $image_folder = '../uploaded_img/' . $image;

       $select_riders = $conn->prepare("SELECT * FROM `riders` WHERE username = ?");
       $select_riders->execute([$username]);

       if ($select_riders->rowCount() > 0) {
           $message[] = 'Username already exists!';
       } else {
           if ($image_size > 2000000) {
               $message[] = 'Image size is too large';
           } else {
               move_uploaded_file($image_tmp_name, $image_folder);

               $insert_rider = $conn->prepare("INSERT INTO `riders` (name, username, password, image) VALUES (?, ?, ?, ?)");
               $insert_rider->execute([$name, $username, $password, $image]);

               $message[] = 'New rider added!';
           }
       }
   }
}



// DELETE RIDERS QUERIES
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_riders_image = $conn->prepare("SELECT * FROM `riders` WHERE id = ?");
   $delete_riders_image->execute([$delete_id]);
   $fetch_delete_image = $delete_riders_image->fetch(PDO::FETCH_ASSOC);
   unlink('../uploaded_img/'.$fetch_delete_image['image']);
   $delete_riders = $conn->prepare("DELETE FROM `riders` WHERE id = ?");
   $delete_riders->execute([$delete_id]);
   header('location:manage_riders.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Riders</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php' ?>

<!-- ADD RIDERS START -->

<section class="add-products">

<form action="" method="POST" enctype="multipart/form-data">
   <h3>Create Riders</h3>
   <input type="text" required placeholder="Full name" name="name" maxlength="100" class="box">
   <input type="text" required placeholder="Username" name="username" class="box">
   <input type="password" required placeholder="Password" name="password" class="box">
   <input type="password" required placeholder="Confirm Password" name="confirm_password" class="box">
   <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png," required>
   <input type="submit" value="Create Riders" name="submit" class="btn">
</form>

</section>

<!-- ADD RIDERS END -->

<section class="show-products" style="padding-top: 0;">
    <h1 style="text-align:center; font-size: 35px; margin-bottom: 20px;">Our Riders : </h1>
   <div class="box-container2">
    <?php
         $select_riders = $conn->prepare("SELECT * FROM `riders`");
         $select_riders->execute();
         $ridersCount = $select_riders->rowCount();

         if ($ridersCount > 0) {
            while ($fetch_riders = $select_riders->fetch(PDO::FETCH_ASSOC)) {  
      ?>
      <div class="box">
         <img src="../uploaded_img/<?= $fetch_riders['image']; ?>" alt="">
         <div class="flex">
            <div class="name"><?= $fetch_riders['name']; ?></div>
         </div>
         <div class="flex-btn">
            <a href="update_rider.php?update=<?= $fetch_riders['id']; ?>" class="option-btn">Update</a>
            <a href="manage_riders.php?delete=<?= $fetch_riders['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this rider?');">Delete</a>
         </div>
      </div>
      <?php
            }
         } else {
            echo '<p class="empty">No Riders Found!</p>';
         }
    ?>
   </div>
</section>
<!-- SHOW RIDERS END -->



<!-- CUSTOM ADMIN JS -->
<script src="../js/admin_script.js"></script>
         

</body>
</html>