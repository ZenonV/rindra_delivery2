<?php
// app/Models/OrderModel.php
class OrderModel extends Database {

    public function getAllOrders() {
        return $this->query("SELECT * FROM orders")->fetchAll(PDO::FETCH_ASSOC);
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
}
?>