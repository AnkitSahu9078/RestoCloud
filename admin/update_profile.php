<?php

include '../components/connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
   header('location:admin_login.php');
   exit();
}

$admin_id = $_SESSION['admin_id'];
$message = [];

if (isset($_POST['submit'])) {

   $name = trim($_POST['name']);
   $name = filter_var($name, FILTER_SANITIZE_STRING);

   if (!empty($name)) {
      $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
      $select_name->execute([$name]);

      if ($select_name->rowCount() > 0) {
         $message[] = 'Username already taken!';
      } else {
         $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
         $update_name->execute([$name, $admin_id]);
         $message[] = 'Username updated successfully!';
      }
   }

   // Fetch existing password
   $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
   $select_old_pass->execute([$admin_id]);
   $fetch_prev_pass = $select_old_pass->fetch(PDO::FETCH_ASSOC);

   if ($fetch_prev_pass) {
      $prev_pass = $fetch_prev_pass['password'];
   } else {
      $prev_pass = "";
   }

   // Get input passwords
   $old_pass = trim($_POST['old_pass']);
   $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
   $new_pass = trim($_POST['new_pass']);
   $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
   $confirm_pass = trim($_POST['confirm_pass']);
   $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

   // Password validation
   if (!empty($old_pass)) {
      if ($old_pass != $prev_pass) {
         $message[] = 'Old password does not match!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'New password and confirm password do not match!';
      } elseif (empty($new_pass)) {
         $message[] = 'Please enter a new password!';
      } else {
         $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
         $update_pass->execute([$confirm_pass, $admin_id]);
         $message[] = 'Password updated successfully!';
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
   <title>Profile Update</title>
   <link rel="icon" href="images/logo.png" type="image/x-icon">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body style="background-image: url('images/2016_09_29_12990_1475116504._large.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

<?php include '../components/admin_header.php'; ?>

<!-- admin profile update section starts  -->

<section class="form-container">
   <form action="" method="POST">
      <h3>Update Profile</h3>
      <input type="text" name="name" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')"
         placeholder="<?= isset($fetch_prev_pass['name']) ? $fetch_prev_pass['name'] : 'Enter new username'; ?>">

      <input type="password" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Update Now" name="submit" class="btn">
   </form>
</section>

<!-- admin profile update section ends -->

<script src="../js/admin_script.js"></script>

</body>
</html>
