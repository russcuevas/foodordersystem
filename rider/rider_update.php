<?php

// INCLUDING CONNECTION TO DATABASE
include '../components/connect.php';

// SESSION IF NOT LOGIN YOU CAN'T GO TO DIRECT PAGE
session_start();
$riders_id = $_SESSION['riders_id'];
if (!isset($riders_id)) {
    header('location:rider_login.php');
}

// FETCH RIDER'S DATA
$fetch_riders = null;
$select_rider = $conn->prepare("SELECT * FROM `riders` WHERE id = ?");
$select_rider->execute([$riders_id]);
$fetch_riders = $select_rider->fetch(PDO::FETCH_ASSOC);

// UPDATE RIDER QUERY
if (isset($_POST['submit'])) {
    $message = [];

    $old_pass = sha1($_POST['old_pass']);
    $new_pass = sha1($_POST['new_pass']);
    $confirm_pass = sha1($_POST['confirm_pass']);

    $select_old_pass = $conn->prepare("SELECT password FROM `riders` WHERE id = ?");
    $select_old_pass->execute([$riders_id]);
    $prev_pass = $select_old_pass->fetchColumn();

    if ($old_pass != $prev_pass) {
        $message[] = '• Old password not matched!';
    } elseif ($new_pass != $confirm_pass) {
        $message[] = '• Confirm password not matched!';
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $_POST['new_pass'])) {
        $message[] = '• Password must be at least 12 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character!';
    }

    $name = $_POST['name'];
    $old_image = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = '• Image size is too large';
        }
    }

    if (empty($message)) {
        $update_pass = $conn->prepare("UPDATE `riders` SET password = ? WHERE id = ?");
        $update_pass->execute([$confirm_pass, $riders_id]);
        $message[] = '• Password successfully updated!';

        if (!empty($image)) {
            $update_profile = $conn->prepare("UPDATE `riders` SET name = ?, image = ? WHERE id = ?");
            $update_profile->execute([$name, $image, $riders_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('../uploaded_img/' . $old_image);
            $message[] = '• Name and image successfully updated!';

            $select_updated_image = $conn->prepare("SELECT image FROM `riders` WHERE id = ?");
            $select_updated_image->execute([$riders_id]);
            $updated_image = $select_updated_image->fetchColumn();
            $fetch_riders['image'] = $updated_image;
        } else {
            $update_profile = $conn->prepare("UPDATE `riders` SET name = ? WHERE id = ?");
            $update_profile->execute([$name, $riders_id]);
            $message[] = '• Name successfully updated!';
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
    <title>Riders Profile Update</title>
    <!-- FONT AWESOME LINK -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CUSTOM ADMIN CSS FILE -->
    <link rel="stylesheet" href="../css/rider_style.css">

</head>

<body>

    <?php include '../components/rider_header.php' ?>


    <!-- RIDERS UPDATE PROFILE STARTS -->

    <section class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">
            <h3 style="margin-bottom: 15px;">Update Profile</h3>
            <?php if (!empty($fetch_riders['image'])) : ?>
                <div class="image-container">
                    <img style="width: 200px; border-radius: 50%; height: 200px;" src="../uploaded_img/<?= $fetch_riders['image']; ?>" alt="Rider Image" class="current-image">
                </div>
            <?php endif; ?>
            <input type="text" required name="name" class="box" value="<?= $fetch_riders['name']; ?>">
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
            <input type="hidden" name="old_image" value="<?= $fetch_riders['image']; ?>">
            <input type="password" name="old_pass" required placeholder="Enter your old password" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" required placeholder="Enter your new password" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_pass" required placeholder="Confirm your new password" class="box"
                oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="submit" value="Update" name="submit" class="btn">
        </form>
    </section>

    <!-- RIDERS UPDATE PROFILE END -->


    <!-- CUSTOM RIDERS JS -->
    <script src="../js/rider_script.js"></script>

</body>

</html>
