<?php
session_start();
require "../config/database.php";

$message = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? AND role='admin'");
    $stmt->execute([$email]);

    if($stmt->rowCount() == 1){

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if(password_verify($password, $admin['password'])){

            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['fullname'] = $admin['fullname'];
            $_SESSION['role'] = "admin";

            header("Location: dashboard.php");
            exit();

        }else{
            $message = "Wrong password.";
        }

    }else{
        $message = "Admin account not found.";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body class="bg-dark">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-4">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h3>Admin Login</h3>
</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-danger">
<?= $message ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100" name="login">
Login
</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>
</html>