<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: /signup/login.php");
    exit();
}
?>
<h1>Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>
