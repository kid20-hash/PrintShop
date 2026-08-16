<?php
require "includes/auth.php";
require "config/database.php";
require "logger.php";

require "config/database.php";

$stmt = $pdo->prepare("
UPDATE orders
SET status_notified = 0
WHERE user_id = ?
AND status_notified = 1
");

$stmt->execute([$_SESSION['user_id']]);

$stmt = $pdo->prepare("
SELECT *
FROM orders
WHERE user_id=?
ORDER BY created_at DESC
");

$stmt->execute([$_SESSION['user_id']]);

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

<title>My Orders</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f8f9fa;
}

/* ===== MOBILE VIEW ONLY ADDITIONS ===== */
@media (max-width: 768px) {

    .container {
        padding-left: 12px;
        padding-right: 12px;
    }

    h2 {
        font-size: 20px;
        text-align: center;
    }

    .table {
        font-size: 13px;
    }

    .badge {
        font-size: 11px;
    }

    .btn {
        width: 100%;
        margin-top: 10px;
    }
}
</style>

</head>

<body>

<div class="container mt-5">

<h2>My Print Orders</h2>

<!-- MOBILE SAFE SCROLL TABLE -->
<div class="table-responsive">

<table class="table table-bordered">

<thead>

<tr>
<th>Queue No.</th>
<th>File</th>
<th>Copies</th>
<th>Paper</th>
<th>Color</th>
<th>Side</th>
<th>Status</th>
<th>Date</th>

</tr>

</thead>

<tbody>

<?php foreach($orders as $row){ ?>

<tr>
<td><strong><?php echo htmlspecialchars($row['queue_number']); ?></strong></td>
<td><?php echo htmlspecialchars($row['filename']); ?></td>

<td><?php echo $row['copies']; ?></td>

<td><?php echo htmlspecialchars($row['paper_size']); ?></td>

<td><?php echo htmlspecialchars($row['print_color']); ?></td>

<td><?php echo htmlspecialchars($row['print_side']); ?></td>

<td>

<span class="badge bg-primary">

<?php echo htmlspecialchars($row['status']); ?>

</span>

</td>

<td><?php echo $row['created_at']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<a
href="dashboard.php"
class="btn btn-secondary">

Back

</a>

</div>

</body>

</html>