<?php
// File: /app/Controllers/UserOrdersController.php

class UserOrdersController {
    private $pdo;
    private $username;

    public function __construct($pdo, $username) {
        $this->pdo = $pdo;
        $this->username = $username;
    }

    public function getUserOrders() {
        $sql = "SELECT * FROM orders WHERE username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$this->username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function confirmDelivery($order_id) {
        $sql = "UPDATE orders SET order_status = 'Delivery Confirmed', user_confirmation = 'Confirmed' WHERE id = ? AND username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$order_id, $this->username]);
    }
}
