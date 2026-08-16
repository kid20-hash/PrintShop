<?php
session_start();
require "config/database.php";
require "logger.php";


$message = "";

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);

    if($stmt->rowCount()==1){

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(password_verify($password,$user['password'])){

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];


            $token = bin2hex(random_bytes(32));

setcookie(
    "remember_token",
    $token,
    time() + (86400 * 30),
    "/",
    "",
    false,
    true
);

$hash = hash('sha256', $token);

$stmt = $pdo->prepare("UPDATE users SET remember_token=? WHERE id=?");
$stmt->execute([$hash, $user['id']]);
             
            $userLog = sprintf(
    "[%s] LOGIN | User: %s | Email: %s | Role: %s | IP: %s\n",
    date('Y-m-d H:i:s'),
    $user['fullname'],
    $user['email'],
    $user['role'],
    $_SERVER['REMOTE_ADDR']
);

file_put_contents(
    "C:/AppServ/Apache24/logs/user_access.log",
    $userLog,
    FILE_APPEND
);





            if($user['role']=="admin"){
                header("Location: admin/dashboard.php");
            }else{
                header("Location: dashboard.php");
            }

            exit();

        }else{
            $message = "Incorrect password.";
        }

    }else{

        $message = "Email not found.";

    }

}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="assets/css/bootstrap.min.css">

<style>
body {
    background:#f8f9fa;
}

/* ===== MOBILE VIEW ADDITIONS ONLY ===== */
@media (max-width: 768px) {

    .container {
        padding-left: 15px;
        padding-right: 15px;
    }

    .card {
        margin-top: 20px;
        border-radius: 12px;
    }

    .card-header h3 {
        font-size: 20px;
        text-align: center;
    }

    .card-body {
        padding: 20px;
    }

    input.form-control {
        font-size: 16px;
        padding: 10px;
    }

    button.btn {
        font-size: 16px;
        padding: 10px;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 10px;
    }
}
</style>

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5 col-12">

<div class="card shadow">

<div class="card-header">

<h3>Login</h3>

</div>

<div class="card-body">

<?php
if($message!=""){
echo "<div class='alert alert-danger'>$message</div>";
}
?>

<form method="POST">

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
required>

</div>

<button
class="btn btn-success w-100"
name="login">

Login

</button>

</form>

<hr>

<a href="register.php">

Create an Account

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>