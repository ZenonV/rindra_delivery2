<?php
// File: /app/Controllers/UserOrdersController.php

class UserOrdersController {
    private $pdo;
    private $username;

    public function __construct($pdo, $username) {
        $this->pdo = $pdo;
        $this->username = $username;
    }

    public function getUserOrders($current_page, $records_per_page) {
        $offset = ($current_page - 1) * $records_per_page; // Calculate the offset
        $sql = "SELECT * FROM orders WHERE username = :username LIMIT :limit OFFSET :offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':username', $this->username); // Bind the username parameter
        $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT); // Bind the limit parameter
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // Bind the offset parameter
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    public function getTotalOrders() {
        $sql = "SELECT COUNT(*) FROM orders WHERE username = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':username', $this->username); // Use named parameter
        $stmt->execute();
        return $stmt->fetchColumn(); // Return the total number of orders
    }
    
    

    public function confirmDelivery($order_id) {
        $sql = "UPDATE orders SET order_status = 'Delivery Confirmed', user_confirmation = 'Confirmed' WHERE id = ? AND username = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$order_id, $this->username]);
    }
}
