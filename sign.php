<?php
session_start();
include "/Database.php.";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $user_name = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    $db = new Database();
    $conn = $db->connect();
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ?");
    $stmt->execute([$user_name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_name'] = $user['user_name'];
        echo "Login successful!";
    } else {
        echo "Invalid username or password.";
    }
}
?>
