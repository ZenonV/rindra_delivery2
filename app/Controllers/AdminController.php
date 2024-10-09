<?php
// app/Controllers/AdminController.php
class AdminController {
    private $orderModel;

    public function __construct($pdo) {
        $this->orderModel = new OrderModel($pdo);
    }

    public function handleRequest() {
        // Assign driver
        if (isset($_POST['assign_driver'])) {
            $orderId = $_POST['order_id'];
            $driverName = $_POST['driver_name'];
            $this->orderModel->assignDriver($orderId, $driverName);
        }

        // Cancel assignment
        if (isset($_POST['cancel_assignment'])) {
            $orderId = $_POST['order_id'];
            $this->orderModel->cancelDriverAssignment($orderId);
        }

        // Fetch all orders
        return $this->orderModel->getAllOrders();
    }
}
?>