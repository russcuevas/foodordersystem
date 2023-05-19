<?php 
include '../components/connect.php';

session_start();
$riders_id = $_SESSION['riders_id'];
if(!isset($riders_id)){
    header('location:rider_login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Rider Dashboard</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM RIDER CSS FILE -->
    <link rel="stylesheet" href="../css/rider_style.css">

</head>
<body>

<!-- RIDER HEADER -->
<?php include '../components/rider_header.php' ?>
<!-- ENDS -->
    
    <!-- RIDER DASHBOARD START -->
<section class="dashboard">
    <h1 class="heading">Rider Dashboard</h1>
    <div class="box-container">
    <div class="box">
      <h3>Welcome!</h3>
      <p><?= $fetch_profile['name']; ?></p>
      <a href="update_profile.php" class="btn">Update Profile</a>
   </div>
</section>
    
</body>
</html>


<!-- CUSTOM RIDER JS FILE -->
<script src="../js/rider_script.js"></script>