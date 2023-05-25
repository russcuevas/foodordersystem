<?php

include 'components/connect.php';

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];
    
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE verification_code = ?");
    $select_user->execute([$verificationCode]);
    $row = $select_user->fetch(PDO::FETCH_ASSOC);
    
    if ($select_user->rowCount() > 0) {
        $user_id = $row['id'];
        
        $update_user = $conn->prepare("UPDATE `users` SET status = 1 WHERE id = ?");
        $update_user->execute([$user_id]);
        
        header("location: login.php?success=1");
        exit();
    } else {
        header("location: login.php?error=1");
        exit();
    }
} else {
    header("location: login.php?error=1");
    exit();
}

?>
