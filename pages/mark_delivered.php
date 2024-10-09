<?php
session_start();
require 'db.php'; // Include the database connection file
include 'app/Controllers/DeliveryController.php'; // Include the DeliveryController class

// Check if the user is logged in as a driver
if (!isset($_SESSION['username']) || $_SESSION['status'] !== 'driver') {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

$controller = new DeliveryController($pdo); // Instantiate the DeliveryController class

// Check if the order ID is provided
if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']); // Get the order ID from the form
    
    // Mark the order as delivered using the controller
    $controller->markAsDelivered($order_id);
    
    header('Location: driver_dashboard.php'); // Redirect back to driver dashboard
    exit();
} else {
    echo "Order ID not provided.";
}
