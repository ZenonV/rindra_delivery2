<?php
session_start();
include '../config.php'; // Adjust path as necessary
include '../app/Controllers/OrderController.php'; // Include the OrderController class

// Redirect if the user is not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$controller = new OrderController($pdo); // Instantiate the OrderController class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];

    // Create a new order using the controller
    $controller->createOrder($username, $product_name, $quantity);

    // Redirect back to the orders page
    header('Location: user_orders.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make an Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="orders_style.css"> <!-- Link to your existing CSS file -->
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Make an Order</h2>

    <form action="make_order.php" method="POST">
        <div class="mb-3">
            <label for="product_name" class="form-label">Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Order</button>
    </form>

    <a href="user_orders.php" class="btn btn-secondary mt-3">Back to Orders</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
