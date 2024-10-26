<?php
session_start();
include "Database.php"; 

// Yhteys tietokantaan
$db = new Database();
$conn = $db->connect();

// Viesti käyttäjän luomisen tai poistamisen jälkeen
$message = "";

// Käyttäjän lisääminen
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['username'], $_POST['fullname'], $_POST['email'], $_POST['password'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token");
    }
    $user_name = htmlspecialchars($_POST["username"]);
    $full_name = htmlspecialchars($_POST["fullname"]);
    $email = htmlspecialchars($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (user_name, full_name, email, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$user_name, $full_name, $email, $password])) {
        $message = "User created successfully!";
    } else {
        $message = "Error creating user.";
    }
}

// Käyttäjän poisto
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_user_id'])) {
    $delete_user_id = $_POST['delete_user_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    if ($stmt->execute([$delete_user_id])) {
        $message = "User deleted successfully!";
    } else {
        $message = "Error deleting user.";
    }
}

// Käyttäjien hakeminen tietokannasta
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link rel="stylesheet" href="new_user_style.css"> 
</head>
<body>
    <div class="form-wrapper">
        <div class="form-container">
            <!-- Message -->
            <?php if (!empty($message)): ?>
                <p class="success-message"><?php echo $message; ?></p>
            <?php endif; ?>

            <h2>All Users</h2>
            <div class="user-list">
                <?php foreach ($users as $user): ?>
                    <div class="user-card">
                        <div class="user-info">
                            <!-- Username -->
                            <div class="info-row">
                                <span><strong>Username:</strong> <?php echo htmlspecialchars($user['user_name']); ?></span>
                                <a href="register.php" class="new-user-button">New User</a>
                            </div>
                            <!-- Full Name -->
                            <div class="info-row">
                                <span><strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name']); ?></span>
                                <a href="index.php" class="home-button">Sing in</a>
                            </div>
                            <!-- Email and Delete -->
                            <div class="info-row">
                                <span><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></span>
                                <form action="new_user.php" method="POST" class="delete-form" style="display:inline;">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $user['user_id']; ?>">
                                    <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
