<?php
require "../config/database.php";
require "auth.php";

if(isset($_POST['id']) && isset($_POST['status'])){

    $id = $_POST['id'];
    $status = $_POST['status'];

    // Get current status
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id=?");
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    // Only update if the status changed
    if($order && $order['status'] != $status){

        $stmt = $pdo->prepare("
            UPDATE orders
            SET
                status = ?,
                status_notified = 1
            WHERE id = ?
        ");

        $stmt->execute([$status, $id]);
    }

}

header("Location: dashboard.php");
exit;