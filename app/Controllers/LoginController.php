<?php
// File: /app/Controllers/LoginController.php

class LoginController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function login($email, $password) {
        // Check if admin credentials are used
        if ($email == 'admin@gmail.com' && $password == 'admin123') {
            $this->setSession('admin', 'admin');
            $this->redirect('admin_dashboard.php');
        }

        // Check for driver credentials
        if ($email == 'driver1@gmail.com' && $password == 'driver123') {
            $this->setSession('Driver1', 'driver');
            $this->redirect('driver_dashboard.php');
        }
        if ($email == 'driver2@gmail.com' && $password == 'driver123') {
            $this->setSession('Driver2', 'driver');
            $this->redirect('driver_dashboard.php');
        }

        // For regular users, check the database
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $this->setSession($user['username'], $user['status']);
            $this->redirect('user_orders.php');
        } else {
            return 'Invalid credentials';
        }
    }

    private function setSession($username, $status) {
        $_SESSION['username'] = $username;
        $_SESSION['status'] = $status;
    }

    private function redirect($location) {
        header('Location: ' . $location);
        exit();
    }
}
