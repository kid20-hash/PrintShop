<?php

$host="localhost";
$db="printshop";
$user="root";
$pass="piatos12";

try{

$pdo=new PDO(
"mysql:host=$host;dbname=$db;charset=utf8",
$user,
$pass
);

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}catch(PDOException $e){

die($e->getMessage());

}

?>