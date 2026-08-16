<?php
require "../config/database.php";
require "auth.php";

$id = $_POST['id'];
$status = $_POST['status'];

$stmt = $pdo->prepare("
UPDATE orders
SET status=?
WHERE id=?
");

$stmt->execute([
$status,
$id
]);

header("Location: orders.php");