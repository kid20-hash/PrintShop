<?php
require "../config/database.php";
require "auth.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("
SELECT filepath,filename
FROM orders
WHERE id=?
");

$stmt->execute([$id]);

$file = $stmt->fetch(PDO::FETCH_ASSOC);

$path = "../uploads/".$file['filepath'];

if(file_exists($path)){

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"".$file['filename']."\"");
readfile($path);

}