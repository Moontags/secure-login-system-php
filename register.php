<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a new account</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <h2>Create a new account</h2> 
            <form action="new_user.php" method="POST">
              
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="text" name="username" placeholder="Username" required>
                <input type="text" name="fullname" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Register</button>
            </form>
            
            <p>Already registered? <a href="login.php">Sign in</a></p>
        </div>
    </div>
</body>
</html>
