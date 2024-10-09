<?php
// app/Controllers/AdminController.php
class AdminController {
    private $orderModel;

    public function __construct($pdo) {
        $this->orderModel = new OrderModel($pdo);
    }

    public function handleRequest($search, $orderStatus, $page) {
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
    
        // Delete order
        if (isset($_POST['delete_order'])) {
            $orderId = $_POST['order_id'];
            $this->orderModel->deleteOrder($orderId);
        }
    
        // Fetch orders with pagination
        return $this->orderModel->getAllOrders($search, $orderStatus, $page);
    }
    
    public function getTotalPages($search, $orderStatus, $recordsPerPage) {
        $totalOrders = $this->orderModel->getTotalOrderCount($search, $orderStatus);
        return ceil($totalOrders / $recordsPerPage);
    }
    
    
}
?>