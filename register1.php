<?php
require "config/database.php";

$message = "";

if (isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $photo = $_POST['photo'];

    if (empty($photo)) {
        $message = "Face verification required.";
    } else {

        $check = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $message = "Email already exists.";
        } else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $photo = str_replace('data:image/jpeg;base64,', '', $photo);
            $photo = str_replace(' ', '+', $photo);

            $imageData = base64_decode($photo);

            if (!is_dir("uploads/faces")) {
                mkdir("uploads/faces", 0777, true);
            }

            $filename = uniqid() . ".jpg";

            file_put_contents("uploads/faces/" . $filename, $imageData);

            $stmt = $pdo->prepare("
                INSERT INTO users (fullname, email, password, face_image)
                VALUES (?, ?, ?, ?)
            ");

            $stmt->execute([
                $fullname,
                $email,
                $hash,
                $filename
            ]);

            header("Location: login.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body { background:#f4f6f9; }
video { width:100%; border-radius:10px; }
canvas { display:none; }
#status { font-weight:bold; font-size:18px; }
</style>
</head>

<body>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card shadow">

<div class="card-header bg-primary text-white">
<h3>Create Account (Liveness Required)</h3>
</div>

<div class="card-body">

<?php if ($message != ""): ?>
<div class="alert alert-danger"><?= $message ?></div>
<?php endif; ?>

<div class="text-center">

<video id="video" autoplay playsinline></video>
<canvas id="canvas"></canvas>

<br><br>

<div id="status" class="text-danger">
Starting camera...
</div>

</div>

<hr>

<form method="POST">

<input type="hidden" name="photo" id="photo">

<div class="mb-3">
<label>Full Name</label>
<input type="text" name="fullname" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Password</label>
<input type="password" name="password" class="form-control" required>
</div>

<button class="btn btn-primary w-100" name="register">
Register
</button>

</form>

</div>
</div>

</div>
</div>
</div>

<script type="module" src="assets/js/register.js"></script>

</body>
</html>