<?php
session_start();
require 'db.php';

// Check if the driver is logged in
if (!isset($_SESSION['username']) || $_SESSION['status'] !== 'driver') {
    header('Location: login.php');
    exit();
}

$driver_email = $_SESSION['username'];

// Fetch the driver's assigned orders
$sql = "SELECT d.id AS driver_order_id, o.id AS order_id, o.product_name, o.quantity, o.order_status, o.delivered, o.user_confirmation 
        FROM drivers d
        JOIN orders o ON d.product_name = o.product_name
        WHERE d.driver_name = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$driver_email]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Confirm Delivery button
if (isset($_POST['confirm_delivery'])) {
    $order_id = $_POST['order_id'];
    $sql = "UPDATE orders SET order_status = 'Delivered', delivered = true WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);
    
}

// Handle Cancel Delivery button
if (isset($_POST['cancel_delivery'])) {
    $order_id = $_POST['order_id'];
    $sql = "UPDATE orders SET order_status = 'Pending', delivered = false WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);

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
