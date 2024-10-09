<?php
// app/Controllers/DeliveryController.php
class DeliveryController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Mark an order as delivered
    public function markAsDelivered($order_id) {
        $sql = "UPDATE orders SET delivered = true WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$order_id]);
    }
}
?>