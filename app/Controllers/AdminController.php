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
    
        // Search orders by username, product name, order date, or order status
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $orderStatus = isset($_GET['order_status']) ? $_GET['order_status'] : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page or default to 1
    
        // Fetch orders with pagination
        return $this->orderModel->getAllOrders($search, $orderStatus, $page);
    }
    public function getTotalPages($search, $orderStatus, $recordsPerPage) {
        $totalOrders = $this->orderModel->getTotalOrderCount($search, $orderStatus);
        return ceil($totalOrders / $recordsPerPage);
    }
    
    
}
?>