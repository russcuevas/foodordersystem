<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
}

// UPDATE RIDERS QUERIES
if (isset($_POST['update'])) {
    $rid = $_POST['rid'];
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
        $message[] = '• Password and confirm password do not match.';
    } else {
        $password = sha1($password);

        $update_rider = $conn->prepare("UPDATE `riders` SET name = ?, username = ?, password = ? WHERE id = ?");
        $update_rider->execute([$name, $username, $password, $rid]);

        $message[] = 'RIDER SUCCESSFULLY UPDATED!';

        $old_image = $_POST['old_image'];
        $image = $_FILES['image']['name'];

        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = '../uploaded_img/' . $image;

        if (!empty($image)) {
            if ($image_size > 2000000) {
                $message[] = 'IMAGE SIZE IS TOO LARGE';
            } else {
                $update_image = $conn->prepare("UPDATE `riders` SET image = ? WHERE id = ?");
                $update_image->execute([$image, $rid]);
                move_uploaded_file($image_tmp_name, $image_folder);
                unlink('../uploaded_img/' . $old_image);
                $message[] = 'IMAGE UPDATED!';
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
   <title>Update Riders</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<!-- INCLUDING HEADER -->
<?php include '../components/admin_header.php' ?>
<!-- HEADER ENDS -->

<!-- UPDATE RIDERS START -->
<section class="update-product">

   <h1 class="heading">Update Rider</h1>

   <?php
   $update_id = $_GET['update'];
   $show_riders = $conn->prepare("SELECT * FROM `riders` WHERE id = ?");
   $show_riders->execute([$update_id]);
   if ($show_riders->rowCount() > 0) {
       while ($fetch_riders = $show_riders->fetch(PDO::FETCH_ASSOC)) {
           ?>
           <form action="" method="POST" enctype="multipart/form-data">
               <input type="hidden" name="rid" value="<?= $fetch_riders['id']; ?>">
               <input type="hidden" name="old_image" value="<?= $fetch_riders['image']; ?>">
               <img src="../uploaded_img/<?= $fetch_riders['image']; ?>" alt="">
               <span>Update Name</span>
               <input type="text" required name="name" class="box" value="<?= $fetch_riders['name']; ?>">
               <span>Update Username</span>
               <input type="text" required name="username" class="box" value="<?= $fetch_riders['username']; ?>">
               <span>Update Password</span>
               <input type="password" required name="password" class="box">
               <span>Confirm Password</span>
               <input type="password" required name="confirm_password" class="box">
               <span>Update Image</span>
               <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
               <div class="flex-btn">
                   <input type="submit" value="Update" class="btn" name="update">
                   <a href="manage_riders.php" style="background-color: #e74c3c;" class="option-btn" onmouseover="this.style.backgroundColor='#231F20';" onmouseout="this.style.backgroundColor='#e74c3c';">Go Back</a>
               </div>
           </form>
           <?php
       }
   } else {
       echo '<p class="empty">NO RIDER FOUND!</p>';
   }
   ?>

</section>
<!-- UPDATE RIDERS END -->



<!-- CUSTOM ADMIN JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>