// admin_dashboard.php
<?php
session_start();
if ($_SESSION['status'] !== 'admin') {
    header('Location: login.php');
    exit;
}
echo "Welcome, Admin!";
?>

// driver_dashboard.php
<?php
session_start();
if ($_SESSION['status'] !== 'driver') {
    header('Location: login.php');
    exit;
}
echo "Welcome, Driver!";
?>

// user_dashboard.php
<?php
session_start();
if ($_SESSION['status'] !== 'user') {
    header('Location: login.php');
    exit;
}
echo "Welcome, User!";
?>
