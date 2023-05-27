<?php
// INCLUDING DATABASE CONNECTION
include 'components/connect.php';

// SESSION IF NOT LOGIN YOU CANT GO TO DIRECT PAGE
session_start();
if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else{
   $user_id = '';
   header('location:home.php');
};

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $address = $_POST['address'];
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // CHECK IF THE NEW PASSWORD IS VALID
    $uppercase = preg_match('@[A-Z]@', $new_pass);
    $lowercase = preg_match('@[a-z]@', $new_pass);
    $passnum = preg_match('@[0-9]@', $new_pass);
    $specialChars = preg_match('@[^\w]@', $new_pass);

    if(empty($name)){
        $message[] = '• Name is required!';
    } elseif(!$uppercase || !$lowercase || !$passnum || !$specialChars || strlen($new_pass) < 12) {
        $message[] = '• New password must contain at least 12 characters, including uppercase letters, lowercase letters, and special characters.';
    } else {
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?");
        $select_user->execute([$email, $number]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);

        if($select_user->rowCount() > 0 && $row['id'] != $_SESSION['user_id']){
            $message[] = '• Email or number already exists!';
        } else {
            if(!empty($old_pass) && !empty($new_pass) && !empty($confirm_pass)){
                // CHECK IF THE OLD PASSWORD IS CORRECT
                $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ? AND password = ?");
                $select_user->execute([$_SESSION['user_id'], sha1($old_pass)]);
                $row = $select_user->fetch(PDO::FETCH_ASSOC);

                if($select_user->rowCount() == 0){
                    $message[] = '• Old password is incorrect!';
                } elseif($new_pass != $confirm_pass){
                    $message[] = '• Confirm password not matched!';
                } elseif($old_pass == $new_pass){
                    $message[] = '• New password should be different from the old password!';
                } else {
                    $update_user = $conn->prepare("UPDATE `users` SET name=?, email=?, number=?, address=?, password=? WHERE id=?");
                    $update_user->execute([$name, $email, $number, $address, sha1($new_pass), $_SESSION['user_id']]);
                    $message[] = '• Profile updated successfully!';
                }
            } else {
                $update_user = $conn->prepare("UPDATE `users` SET name=?, email=?, number=?, address=? WHERE id=?");
                $update_user->execute([$name, $email, $number, $address, $_SESSION['user_id']]);
                $message[] = '• Profile updated successfully!';
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
    <title>Update Profile</title>
    <!-- LINK IN FAVICON -->
    <link rel="shortcut icon" href="favicon/profile.png" type="image/x-icon">
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS LINK  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- INCLUDING HEADER -->
<?php include 'components/user_header.php';?>
<!-- HEADER END -->

<section class="form-container update-form">

   <form action="" method="post">
      <h3>Update Profile</h3>
      <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" class="box" maxlength="50">
      <input type="hidden" name="email" value="<?= $fetch_profile['email']; ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" value="<?= $fetch_profile['number']; ?>" class="box" min="0" max="9999999999" maxlength="11">
      <input type="text" name="address" required value="<?= $fetch_profile['address']; ?>" class="box">
      <input type="password" name="old_pass" placeholder="Enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="Confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="update now" name="submit" class="btn">
   </form>

</section>






<!-- CUSTOM JS FILE -->
<script src="js/script.js"></script>

</body>
</html>