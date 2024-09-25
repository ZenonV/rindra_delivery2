<?php
session_start();
include 'config.php';

// Redirect if the user is not an admin
if (!isset($_SESSION['username']) || $_SESSION['status'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all orders
$sql = "SELECT * FROM orders";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch drivers (for dropdown selection)
$drivers_list = ['Driver1', 'Driver2', 'Driver3']; // Example drivers list

// Assign driver when the form is submitted
if (isset($_POST['assign_driver'])) {
    $order_id = $_POST['order_id'];
    $driver_name = $_POST['driver_name'];
    
    // Fetch the order details
    $sql = "SELECT product_name, quantity FROM orders WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    // Insert into drivers table with order_id
    $sql = "INSERT INTO drivers (product_name, driver_name, quantity, order_id) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$order['product_name'], $driver_name, $order['quantity'], $order_id]);

    // Update the order to reflect driver assignment
    $sql_update = "UPDATE orders SET driver_assigned = 1 WHERE id = ?"; // Corrected line
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$order_id]);
}

// Cancel driver assignment when the form is submitted
if (isset($_POST['cancel_assignment'])) {
    $order_id = $_POST['order_id'];
    
    // Update the orders table to set driver_assigned to false
    $sql_update = "UPDATE orders SET driver_assigned = 0, driver_assigned = 'No' WHERE id = ?";
    $stmt_update = $pdo->prepare($sql_update);
    $stmt_update->execute([$order_id]);

   // Optionally, you may also want to remove the corresponding entry from the drivers table
$sql_delete = "DELETE FROM drivers WHERE order_id = ?";
$stmt_delete = $pdo->prepare($sql_delete);
$stmt_delete->execute([$order_id]);


    // Redirect to the same page to refresh the data
    header("Location: admin_dashboard.php");
    exit();
}


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
                    <th>Driver Assignment</th> 
                    <th>Driver Assignment</th>

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
        <td><?= $order['driver_assigned'] ? 'Yes' : 'No' ?></td> <!-- Updated Driver Assignment Status -->
        <td>
            <?php if ($order['driver_assigned']): ?>
                <span class="badge bg-success">Assigned</span> <!-- Green "Assigned" badge -->
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

