<?php 
if(isset($message)){
    foreach($message as $message){
        echo '<div class="message">
        <span>'.$message.'</span>
        <i class="fas fa-times" onclick=".this.parentElement.remove();"></i>
    </div>';
    }
}
?>

<header class="header">
    <section class="flex">
        <a href="home.php" class="logo">FOOD ORDER SYSTEM</a>
        <nav class="navbar">
            <a href="home.php">Home</a>
            <a href="about.php">About</a>
            <a href="menu.php">Menu</a>
            <a href="orders.php">Orders</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="icons">
            <?php
                $count_user_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $count_user_cart_items->execute(['user_id']);
                $total_user_cart_items = $count_user_cart_items->rowCount();
            ?>
        <a href="search.php"><i class="fas fa-search"></i></a>
        <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_user_cart_items; ?>)</span></a>
        <div id="user-btn" class="fas fa-user"></div>
        <div id="menu-btn" class="fas fa-bars"></div>
        </div>
    </section>
</header>