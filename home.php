<?php 

// INCLUDING DATABASE
include 'components/connect.php';

if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- SWIPER LINK -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM CSS LINK -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<!-- HEADER STARTS -->
<!-- INCLUDING THE USER HEADER FILE -->
<?php include 'components/user_header.php' ?>
<!-- HEADER ENDS -->


<!-- CUSTOM JS USER FILE -->
<script src="../js/script.js"></script>
</body>
</html>