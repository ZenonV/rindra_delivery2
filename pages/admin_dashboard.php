<?php
session_start();
include '../config.php';
include '../app/Models/Database.php';
include '../app/Models/OrderModel.php';
include '../app/Controllers/AdminController.php';

$controller = new AdminController($pdo);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$orderStatus = isset($_GET['order_status']) ? $_GET['order_status'] : '';
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;

// Fetch orders based on search, filters, and pagination
$orders = $controller->handleRequest($search, $orderStatus, $current_page);

// Get total pages for pagination
$total_pages = $controller->getTotalPages($search, $orderStatus, $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_style.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">Admin Dashboard - All Orders</h2>

    <!-- Search and Filter Form -->
    <form method="GET" action="admin_dashboard.php" class="mb-4 text-center">
        <input type="text" name="search" placeholder="Search by Username, Product Name, or Order Date" class="form-control" style="width: 400px; display: inline-block;" value="<?= htmlspecialchars($search) ?>">
        <select name="order_status" class="form-select" style="width: 200px; display: inline-block;">
            <option value="">Select Order Status</option>
            <option value="Pending" <?= $orderStatus == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="Delivered" <?= $orderStatus == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
            <option value="Delivery Confirmed" <?= $orderStatus == 'Delivery Confirmed' ? 'selected' : '' ?>>Delivery Confirmed</option>
        </select>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <!-- Orders Table -->
    <div id="orderData" class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Product Name</th>
                    <th>Order Date</th>
                    <th>Quantity</th>
                    <th>Order Status</th>
                    <th>Driver Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td><?= htmlspecialchars($order['quantity']) ?></td>
                        <td><?= htmlspecialchars($order['order_status']) ?></td>
                        <td><?= $order['driver_assigned'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <!-- Action buttons here -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center">
        <!-- Previous arrow -->
        <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= max(1, $current_page - 1) ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Page numbers -->
        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
            <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                <a class="page-link text-center" href="?page=<?= $i ?>" style="border-radius: 10px; width: 60px; height: 60px;">
                    <strong><?= $i ?></strong>
                    <small class="d-block text-muted">Page</small> <!-- Text below the number -->
                </a>
            </li>
        <?php endfor; ?>

        <!-- Next arrow -->
        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= min($total_pages, $current_page + 1) ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>




    
</div>

</body>
</html>
