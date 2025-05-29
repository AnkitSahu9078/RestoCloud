<?php

include '../components/connect.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
   header('location:admin_login.php');
   exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin details
$select_admin = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_admin->execute([$admin_id]);
$fetch_profile = $select_admin->fetch(PDO::FETCH_ASSOC);

// Check if data is retrieved before accessing it
$admin_name = isset($fetch_profile['name']) ? $fetch_profile['name'] : 'Unknown Admin';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="icon" href="images/logo.png" type="image/x-icon">

   <!-- Font Awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body style="background-image: url('images/food-1024x683.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">

<?php include '../components/admin_header.php'; ?>

<!-- Admin Dashboard Section -->
<section class="dashboard">

   <h1 class="heading">Dashboard</h1>

   <div class="box-container">

      <div class="box">
         <h3>Welcome!</h3>
         <p><?= $admin_name; ?></p>
         <a href="update_profile.php" class="btn">Update Profile</a>
      </div>

      <div class="box">
         <?php
            $total_pendings = 0;
            $select_pendings = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
            $select_pendings->execute(['pending']);
            while ($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
               $total_pendings += $fetch_pendings['total_price'];
            }
         ?>
         <h3><span>₹</span><?= number_format($total_pendings, 2); ?><span>/-</span></h3>
         <p>Total Pendings</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <?php
            $total_completes = 0;
            $select_completes = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
            $select_completes->execute(['completed']);
            while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
               $total_completes += $fetch_completes['total_price'];
            }
         ?>
         <h3><span>₹</span><?= number_format($total_completes, 2); ?><span>/-</span></h3>
         <p>Total Completes</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <?php
            $select_orders = $conn->prepare("SELECT COUNT(*) AS count FROM `orders`");
            $select_orders->execute();
            $numbers_of_orders = $select_orders->fetch(PDO::FETCH_ASSOC)['count'];
         ?>
         <h3><?= $numbers_of_orders; ?></h3>
         <p>Total Orders</p>
         <a href="placed_orders.php" class="btn">See Orders</a>
      </div>

      <div class="box">
         <?php
            $select_products = $conn->prepare("SELECT COUNT(*) AS count FROM `products`");
            $select_products->execute();
            $numbers_of_products = $select_products->fetch(PDO::FETCH_ASSOC)['count'];
         ?>
         <h3><?= $numbers_of_products; ?></h3>
         <p>Products Added</p>
         <a href="products.php" class="btn">See Products</a>
      </div>

      <div class="box">
         <?php
            $select_users = $conn->prepare("SELECT COUNT(*) AS count FROM `users`");
            $select_users->execute();
            $numbers_of_users = $select_users->fetch(PDO::FETCH_ASSOC)['count'];
         ?>
         <h3><?= $numbers_of_users; ?></h3>
         <p>User Accounts</p>
         <a href="users_accounts.php" class="btn">See Users</a>
      </div>

      <div class="box">
         <?php
            $select_admins = $conn->prepare("SELECT COUNT(*) AS count FROM `admin`");
            $select_admins->execute();
            $numbers_of_admins = $select_admins->fetch(PDO::FETCH_ASSOC)['count'];
         ?>
         <h3><?= $numbers_of_admins; ?></h3>
         <p>Admins</p>
         <a href="admin_accounts.php" class="btn">See Admins</a>
      </div>

      <div class="box">
         <?php
            $select_messages = $conn->prepare("SELECT COUNT(*) AS count FROM `messages`");
            $select_messages->execute();
            $numbers_of_messages = $select_messages->fetch(PDO::FETCH_ASSOC)['count'];
         ?>
         <h3><?= $numbers_of_messages; ?></h3>
         <p>New Messages</p>
         <a href="messages.php" class="btn">See Messages</a>
      </div>

   </div>

</section>
<!-- End Admin Dashboard Section -->

<!-- Custom JS -->
<script src="../js/admin_script.js"></script>

</body>
</html>
