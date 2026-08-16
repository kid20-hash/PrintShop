<?php
session_start();
require "config/database.php";

$stmt = $pdo->prepare("
SELECT queue_number
FROM orders
WHERE user_id = ?
AND status = 'Ready'
ORDER BY created_at DESC
LIMIT 1
");

$stmt->execute([$_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if ($order) {
    echo '<div class="alert alert-success">
            <strong>' . htmlspecialchars($order['queue_number']) . '</strong> is now <strong>READY</strong> for pickup.
          </div>';
}