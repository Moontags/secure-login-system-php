<?php
session_start();


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }

    $user_name = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);

    include "Database.php"; 
    $db = new Database();
    $conn = $db->connect();

    // Check if the username exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ?");
    $stmt->execute([$user_name]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true); // Regenerate session ID for security
        
        $_SESSION['user_name'] = $user['user_name'];
        echo "Login successful!";
        exit();
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <h2>Sign in</h2> 
            <form action="login.php" method="POST">
                <!-- CSRF token -->
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Login fields -->
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Sign in</button>
            </form>
            
            <!-- Registration link -->
            <p>Not registered yet?</p>
            <p><a href="register.php">Create a new account</a></p>
        </div>
    </div>
</body>
</html>
