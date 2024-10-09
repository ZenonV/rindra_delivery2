<?php
session_start();
require_once '../config.php';
require_once '../app/Controllers/UserOrdersController.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

// Instantiate the controller
$userOrdersController = new UserOrdersController($pdo, $username);

// Handle Confirm Delivery button
if (isset($_POST['confirm_delivery'])) {
    $order_id = $_POST['order_id'];
    $userOrdersController->confirmDelivery($order_id);
}

// Pagination setup
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$records_per_page = 10; // Number of orders per page
$total_orders = $userOrdersController->getTotalOrders(); // Total number of orders
$total_pages = ceil($total_orders / $records_per_page); // Total number of pages

// Fetch user orders for the current page
$orders = $userOrdersController->getUserOrders($current_page, $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Your Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/orders_style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Your Orders</h2>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Order Date</th>
                    <th>Quantity</th>
                    <th>Order Status</th>
                    <th>Confirm Delivery</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="<?= $order['order_status'] === 'Delivery Confirmed' ? 'table-success' : '' ?>">
                            <td><?= $order['id'] ?></td>
                            <td><?= htmlspecialchars($order['product_name']) ?></td>
                            <td><?= $order['order_date'] ?></td>
                            <td><?= $order['quantity'] ?></td>
                            <td><?= $order['order_status'] ?></td>
                            <td>
                                <?php if ($order['order_status'] === 'Pending' || $order['order_status'] === 'Delivered'): ?>
                                    <!-- Confirm Delivery button -->
                                    <form method="POST" action="user_orders.php">
                                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                        <button type="submit" name="confirm_delivery" class="btn btn-outline-success btn-sm">Confirm Delivery</button>
                                    </form>
                                <?php elseif ($order['order_status'] === 'Delivery Confirmed'): ?>
                                    <span class="badge bg-success">Delivery Confirmed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">You have no orders.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= max(1, $current_page - 1) ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= min($total_pages, $current_page + 1) ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="d-flex justify-content-center mt-4">
        <a href="make_order.php" class="btn btn-primary btn-lg">Make an Order</a>
    </div>
</div>
</body>
</html>
