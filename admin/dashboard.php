<?php
require "../config/database.php";
require "auth.php";

$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role='customer'")->fetchColumn();

$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

$pending = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='Pending'")->fetchColumn();

$completed = $pdo->query("SELECT COUNT(*) FROM orders WHERE status='Completed'")->fetchColumn();
?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-dark bg-dark">

<div class="container">

<span class="navbar-brand">
Print Shop Admin
</span>

<div>

<a href="orders.php" class="btn btn-warning">
Orders
</a>

<a href="../logout.php" class="btn btn-danger">
Logout
</a>

</div>

</div>

</nav>

<div class="container mt-4">

<div class="row">

<div class="col-md-3">

<div class="card text-center">

<div class="card-body">

<h2><?php echo $totalUsers; ?></h2>

Customers

</div>

</div>

</div>

<div class="col-md-3">

<div class="card text-center">

<div class="card-body">

<h2><?php echo $totalOrders; ?></h2>

Orders

</div>

</div>

</div>

<div class="col-md-3">

<div class="card text-center">

<div class="card-body">

<h2><?php echo $pending; ?></h2>

Pending

</div>

</div>

</div>

<div class="col-md-3">

<div class="card text-center">

<div class="card-body">

<h2><?php echo $completed; ?></h2>

Completed

</div>

</div>

</div>

</div>

</div>

</body>

</html>