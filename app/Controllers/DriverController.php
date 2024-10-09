<?php
class DriverController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fetch assigned orders for the driver
    public function getAssignedOrders($driver_email) {
        $sql = "SELECT d.id AS driver_order_id, o.id AS order_id, o.product_name, o.quantity, o.order_status, o.delivered, o.user_confirmation 
                FROM drivers d
                JOIN orders o ON d.product_name = o.product_name
                WHERE d.driver_name = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$driver_email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Confirm the delivery of an order
    public function confirmDelivery($order_id) {
        $sql = "UPDATE orders SET order_status = 'Delivered', delivered = true WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$order_id]);
    }

    // Cancel the delivery of an order
    public function cancelDelivery($order_id) {
        $sql = "UPDATE orders SET order_status = 'Pending', delivered = false WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$order_id]);
    }
}
?>