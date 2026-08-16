<?php
require "includes/auth.php";
require "config/database.php";

$stmt = $pdo->prepare("
SELECT COUNT(*) AS total
FROM orders
WHERE user_id=?
AND status_notified=1
");

$stmt->execute([$_SESSION['user_id']]);

$notif = $stmt->fetch();
$unread = $notif['total'];




?>

<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
}

/* ===== MOBILE VIEW ONLY ===== */
@media (max-width: 768px) {

    .navbar .container {
        flex-direction: column;
        align-items: flex-start;
    }

    .navbar .d-flex {
        width: 100%;
        flex-direction: column;
        gap: 8px;
        margin-top: 10px;
    }

    .navbar a.btn {
        width: 100%;
        text-align: center;
    }

    .navbar span.text-white {
        display: block;
        margin-bottom: 10px;
    }

    .card {
        margin-top: 10px;
    }

    h2 {
        font-size: 20px;
    }
}
</style>

</head>

<body>
<script>
setInterval(function () {
    location.reload();
}, 3000); // every 10 seconds
</script>

<script>
function checkReady() {
    fetch("check_ready.php")
        .then(response => response.text())
        .then(data => {
            document.getElementById("readyMessage").innerHTML = data;
        });
}

checkReady();              
setInterval(checkReady, 3000); 
</script>
<nav class="navbar navbar-dark bg-dark">

<div class="container">

<span class="navbar-brand">

Print Shop

</span>

<!-- WRAP BUTTONS FOR MOBILE -->
<div class="d-flex align-items-center">

<span class="text-white me-3">

Welcome,
<?php echo $_SESSION['fullname']; ?>

</span>

<a
href="upload.php"
class="btn btn-warning me-2">

Upload Document

</a>

<a href="my_orders.php" class="btn btn-info me-2 position-relative">
    My Orders

    <?php if ($unread > 0): ?>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= $unread ?>
    </span>
    <?php endif; ?>
</a>

<a
href="logout.php"
class="btn btn-danger">

Logout

</a>

</div>

</div>

</nav>

<div class="container mt-5">

<div class="card shadow">

<div class="card-body">


<div id="readyMessage"></div> 

<h2>

Dashboard

</h2>

<p>

Welcome to the Online Print Shop.

</p>

</div>

</div>

</div>

</body>

</html>