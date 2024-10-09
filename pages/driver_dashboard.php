<?php
session_start();
include '../config.php'; // Adjust path as per your project structure
include '../app/Models/Database.php';
include '../app/Controllers/DriverController.php'; // Include the DriverController class

// Check if the driver is logged in
if (!isset($_SESSION['username']) || $_SESSION['status'] !== 'driver') {
    header('Location: login.php');
    exit();
}

$driver_email = $_SESSION['username'];
$controller = new DriverController($pdo); // Instantiate the DriverController class

// Fetch the driver's assigned orders
$orders = $controller->getAssignedOrders($driver_email);

// Handle Confirm Delivery button
if (isset($_POST['confirm_delivery'])) {
    $order_id = $_POST['order_id'];
    $controller->confirmDelivery($order_id); // Use the controller to confirm delivery
    // Refresh the orders after confirmation
    $orders = $controller->getAssignedOrders($driver_email);
}

// Handle Cancel Delivery button
if (isset($_POST['cancel_delivery'])) {
    $order_id = $_POST['order_id'];
    $controller->cancelDelivery($order_id); // Use the controller to cancel delivery
    // Refresh the orders after cancellation
    $orders = $controller->getAssignedOrders($driver_email);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="admin_style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Welcome, Driver: <?php echo htmlspecialchars($driver_email); ?></h2>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Order Status</th>
                        <th>User Confirmation</th>
                        <th>Delivery Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr class="<?= $order['delivered'] ? 'table-success' : ''; ?>">
                            <td><?= htmlspecialchars($order['product_name']); ?></td>
                            <td><?= htmlspecialchars($order['quantity']); ?></td>
                            <td><?= htmlspecialchars($order['order_status']); ?></td>
                            <td><?= htmlspecialchars($order['user_confirmation'] ?? 'Confirmation Awaiting'); ?></td>
                            <td><?= $order['delivered'] ? 'Delivered' : 'Pending'; ?></td>

                            <td>
                                <?php if (!$order['delivered']): ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                        <button type="submit" name="confirm_delivery" class="btn btn-outline-success btn-sm">Confirm Delivery</button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="">
                                        <input type="hidden" name="order_id" value="<?= $order['order_id']; ?>">
                                        <button type="submit" name="cancel_delivery" class="btn btn-outline-danger btn-sm">Cancel Delivery</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
