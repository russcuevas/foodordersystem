<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
$admin_id = $_SESSION['admin_id'];
if(!isset($admin_id)){
    header('location:admin_login.php');
}

// QUERIES FOR MESSAGES DELETE
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->execute([$delete_id]);
    header('location:messages.php');
}

?>

<!-- MESSAGES PAGE -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Messages</title>

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
    

<!-- USER MESSAGE STARTS -->

<section class="messages">
    <h1 class="heading">User Messages</h1>
    <div class="box-container">
   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_messages = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Name : <span><?= $fetch_messages['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_messages['number']; ?></span> </p>
      <p> Email : <span><?= $fetch_messages['email']; ?></span> </p>
      <p> Message : <span><?= $fetch_messages['message']; ?></span> </p>
      <a href="messages.php?delete=<?= $fetch_messages['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">YOU HAVE NO MESSAGES!</p>';
      }
   ?>
   </div>

</section>
<!-- USER MESSAGE ENDS -->


<!-- CUSTOM ADMIN JS FILE -->
<script src="../js/admin_script.js"></script>

</body>
</html>