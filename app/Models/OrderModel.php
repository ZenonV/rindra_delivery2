<?php
// app/Models/OrderModel.php
class OrderModel extends Database {

    public function getAllOrders($search = '', $orderStatus = '', $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM orders WHERE 1=1"; // Base query
        
        // Search criteria
        if (!empty($search)) {
            $sql .= " AND (username LIKE :search OR product_name LIKE :search OR order_date LIKE :search)";
        }

        // Filter by order status
        if (!empty($orderStatus)) {
            $sql .= " AND order_status = :orderStatus";
        }

        // Add pagination
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        
        // Bind search parameter
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        
        // Bind order status filter
        if (!empty($orderStatus)) {
            $stmt->bindValue(':orderStatus', $orderStatus);
        }
        
        // Bind limit and offset
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    public function assignDriver($orderId, $driverName) {
        // Fetch the order details
        $order = $this->query("SELECT product_name, quantity FROM orders WHERE id = ?", [$orderId])->fetch();

        // Insert into drivers table
        $this->query("INSERT INTO drivers (product_name, driver_name, quantity, order_id) VALUES (?, ?, ?, ?)", 
            [$order['product_name'], $driverName, $order['quantity'], $orderId]);

        // Update the order to reflect driver assignment
        $this->query("UPDATE orders SET driver_assigned = 1 WHERE id = ?", [$orderId]);
    }

    public function cancelDriverAssignment($orderId) {
        // Update the orders table to set driver_assigned to false
        $this->query("UPDATE orders SET driver_assigned = 0 WHERE id = ?", [$orderId]);

        // Remove the corresponding entry from the drivers table
        $this->query("DELETE FROM drivers WHERE order_id = ?", [$orderId]);
    }
    public function getOrders($search = '') {
        $sql = "SELECT * FROM orders WHERE username LIKE ? OR product_name LIKE ? OR order_date LIKE ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search%", "%$search%", "%$search%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalOrderCount($search = '', $orderStatus = '') {
        $sql = "SELECT COUNT(*) FROM orders WHERE 1=1";

        // Apply search and filter criteria
        if (!empty($search)) {
            $sql .= " AND (username LIKE :search OR product_name LIKE :search OR order_date LIKE :search)";
        }
        if (!empty($orderStatus)) {
            $sql .= " AND order_status = :orderStatus";
        }

        $stmt = $this->pdo->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%');
        }
        if (!empty($orderStatus)) {
            $stmt->bindValue(':orderStatus', $orderStatus);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
?>