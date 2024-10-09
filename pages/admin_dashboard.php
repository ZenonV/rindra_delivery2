<?php
session_start();
include '../config.php'; // Adjust if the config file is located outside the `pages` directory

// Include the model classes
include '../app/Models/Database.php';
include '../app/Models/OrderModel.php';
include '../app/Controllers/AdminController.php';

// Redirect if the user is not an admin
if (!isset($_SESSION['username']) || $_SESSION['status'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Instantiate the AdminController and handle the request
$controller = new AdminController($pdo);
$orders = $controller->handleRequest();

// Drivers list
$drivers_list = ['Driver1', 'Driver2', 'Driver3'];
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
    
    <div class="table-responsive">
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
                        <td><?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['username']) ?></td>
                        <td><?= htmlspecialchars($order['product_name']) ?></td>
                        <td><?= $order['order_date'] ?></td>
                        <td><?= $order['quantity'] ?></td>
                        <td><?= $order['order_status'] ?></td>
                        <td><?= $order['driver_assigned'] ? 'Yes' : 'No' ?></td>
                        <td>
                            <?php if ($order['driver_assigned']): ?>
                                <span class="badge bg-success">Assigned</span>
                                <form method="POST" action="admin_dashboard.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button type="submit" name="cancel_assignment" class="btn btn-outline-danger btn-sm mt-1">Cancel</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="admin_dashboard.php" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <select name="driver_name" class="form-select form-select-sm" required>
                                        <option value="">Select Driver</option>
                                        <?php foreach ($drivers_list as $driver): ?>
                                            <option value="<?= $driver ?>"><?= $driver ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" name="assign_driver" class="btn btn-outline-primary btn-sm mt-1">Assign</button>
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
