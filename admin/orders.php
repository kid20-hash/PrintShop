<?php
require "../config/database.php";
require "auth.php";

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

// Count total orders
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalPages = ceil($totalOrders / $limit);

// Get paginated orders
$stmt = $pdo->prepare("
SELECT
    orders.*,
    users.fullname
FROM orders
JOIN users
ON users.id = orders.user_id
ORDER BY orders.created_at DESC
LIMIT :limit OFFSET :offset
");

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>

<title>Manage Orders</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-4">

<h2>Manage Print Orders</h2>

<table class="table table-bordered table-striped">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Queue No.</th>
<th>Customer</th>
<th>Document</th>
<th>Copies</th>
<th>Status</th>
<th>Download</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php if(count($orders) > 0){ ?>

<?php foreach($orders as $row){ ?>

<tr>

<td><?= $row['id']; ?></td>
<td><strong><?= htmlspecialchars($row['queue_number']); ?></strong></td>

<td><?= htmlspecialchars($row['fullname']); ?></td>

<td><?= htmlspecialchars($row['filename']); ?></td>

<td><?= $row['copies']; ?></td>

<td><?= htmlspecialchars($row['status']); ?></td>

<td>

<a
class="btn btn-success btn-sm"
href="download.php?id=<?= $row['id']; ?>">

Download

</a>

</td>

<td>

<form action="update_status1.php" method="POST">

<input
type="hidden"
name="id"
value="<?= $row['id']; ?>">

<select
name="status"
class="form-select">

<option <?= $row['status']=="Pending" ? "selected" : ""; ?>>Pending</option>

<option <?= $row['status']=="Printing" ? "selected" : ""; ?>>Printing</option>

<option <?= $row['status']=="Ready" ? "selected" : ""; ?>>Ready</option>

<option <?= $row['status']=="Completed" ? "selected" : ""; ?>>Completed</option>

</select>

<br>

<button class="btn btn-primary btn-sm">

Update

</button>

</form>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>

<td colspan="7" class="text-center">

No orders found.

</td>

</tr>

<?php } ?>

</tbody>

</table>

<!-- Pagination -->

<nav>

<ul class="pagination justify-content-center">

<?php if($page > 1){ ?>

<li class="page-item">

<a class="page-link" href="?page=<?= $page-1; ?>">

Previous

</a>

</li>

<?php } ?>

<?php for($i=1; $i<=$totalPages; $i++){ ?>

<li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">

<a class="page-link" href="?page=<?= $i; ?>">

<?= $i; ?>

</a>

</li>

<?php } ?>

<?php if($page < $totalPages){ ?>

<li class="page-item">

<a class="page-link" href="?page=<?= $page+1; ?>">

Next

</a>

</li>

<?php } ?>

</ul>

</nav>

<a href="dashboard.php" class="btn btn-secondary">

Dashboard

</a>

</div>

</body>

</html>