<?php
// File: /app/Controllers/RegisterController.php

class RegisterController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function register($username, $email, $password, $confirm_password) {
        // Validate passwords match
        if ($password !== $confirm_password) {
            return "Passwords do not match!";
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username, $email, $hashed_password]);

        return "Registration successful! You can now <a href='login.php'>login</a>.";
    }
}
