<?php

// MESSAGE SA TAAS PARA KITA NG USER KUNG NAG ADD BA OR FAILED ANG GINAGAWA NILA



?>




<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>
        
        <nav class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="products.php">Products</a>
            <a href="placed_orders.php">Orders</a>
            <a href="admin_accounts.php">Admins</a>
            <a href="users_accounts.php">Users</a>
            <a href="messages.php">Messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <p>Name:</p>
            <a href="update_profile.php" class="btn">Update Profile</a>
            <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="delete-btn">Logout</a>
        </div>
    </section>
</header>