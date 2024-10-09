<?php
// app/Controllers/OrderController.php
class OrderController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new order
    public function createOrder($username, $product_name, $quantity) {
        $sql = "INSERT INTO orders (username, product_name, quantity, order_status, delivered) VALUES (?, ?, ?, 'pending', 0)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username, $product_name, $quantity]);
    }
}
?>