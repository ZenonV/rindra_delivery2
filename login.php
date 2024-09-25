<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the admin credentials are used
    if ($email == 'admin@gmail.com' && $password == 'admin123') {
        $_SESSION['username'] = 'admin';
        $_SESSION['status'] = 'admin';
        header('Location: admin_dashboard.php');
        exit();
    }

    // Check if the driver credentials are used (predefined drivers)
    if ($email == 'driver1@gmail.com' && $password == 'driver123') {
        $_SESSION['username'] = 'Driver1';
        $_SESSION['status'] = 'driver';
        header('Location: driver_dashboard.php');
        exit();
    }
    if ($email == 'driver2@gmail.com' && $password == 'driver123') {
        $_SESSION['username'] = 'Driver2';
        $_SESSION['status'] = 'driver';
        header('Location: driver_dashboard.php');
        exit();
    }
    // Add more driver accounts as needed...

    // Regular user login process
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['status'] = $user['status'];
        header('Location: user_orders.php');
        exit();
    } else {
        echo 'Invalid credentials';
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="text-center">Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="text-center mt-3">Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>

