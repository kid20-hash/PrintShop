<?php
require "../config/database.php";

$message = "";

if (isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password != $confirm) {
        $message = "Passwords do not match.";
    } else {

        $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {

            $message = "Email already exists.";

        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users
                (fullname,email,password,role)
                VALUES (?,?,?,'customer')
            ");

            $stmt->execute([
                $fullname,
                $email,
                $hash
            ]);

            header("Location: login.php?success=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-5">

<div class="card shadow">

<div class="card-header">
<h3>Create Account</h3>
</div>

<div class="card-body">

<?php if($message!=""){ ?>

<div class="alert alert-danger">
<?= htmlspecialchars($message) ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Full Name</label>
<input
type="text"
name="fullname"
class="form-control"
required>
</div>

<div class="mb-3">
<label>Email Address</label>
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

<div class="mb-3">
<label>Confirm Password</label>
<input
type="password"
name="confirm_password"
class="form-control"
required>
</div>

<button
type="submit"
name="register"
class="btn btn-primary w-100">

Register

</button>

</form>

<hr>

Already have an account?

<a href="login.php">
Login here
</a>

</div>

</div>

</div>

</div>

</div>

</body>
</html>