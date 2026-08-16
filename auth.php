<?php
require "auth.php";
require "config/database.php";

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {

    $hash = hash('sha256', $_COOKIE['remember_token']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token=?");
    $stmt->execute([$hash]);

    if ($stmt->rowCount() == 1) {

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];
    }
}