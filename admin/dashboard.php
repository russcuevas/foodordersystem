<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Dashboard</title>

    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<!-- ADMIN HEADER -->
<?php include '../components/admin_header.php' ?>
<!-- ENDS -->

<!-- ADMIN DASHBOARD START -->
<section class="dashboard">
    <h1 class="heading">Admin Dashboard</h1>
    <div class="box-container">
        <div class="box">
            <h3>Welcome!</h3>
            <p>[Name]</p>
            <a href="update_profule.php" class="btn">Update Profile</a>
        </div>

        <div class="box">
            <h3><span>P</span>[total_pendings]<span></span></h3>
            <p>Total Pendings</p>
            <a href="placed_orders.php" class="btn">View Orders</a>
        </div>

        <div class="box">
            <h3><span>P</span>[total_completes]<span></span></h3>
            <p>Total Completes</p>
            <a href="placed_orders.php" class="btn">View Orders</a>
        </div>
    </div>
</section>

</body>
</html>