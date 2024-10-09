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

// Fetch user orders
$orders = $userOrdersController->getUserOrders();

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

    <div class="d-flex justify-content-center mt-4">
        <a href="make_order.php" class="btn btn-primary btn-lg">Make an Order</a>
    </div>
</div>
</body>
</html>
