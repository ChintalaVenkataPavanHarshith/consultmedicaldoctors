<?php
session_start();
include('db_config.php');

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $conn = getPDOConnection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: doctors.php");
        exit();  
    } else {
        $error = "Invalid login credentials.";
    }
}

if (isset($_POST['signup'])) {
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $conn = getPDOConnection();
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        $_SESSION['user_id'] = $conn->lastInsertId();
        header("Location: doctors.php");
        exit();  
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
    <link rel="stylesheet" href="styles.css">  <!-- Link to styles.css -->
</head>
<body>
    <div class="container">
        <h2>Medical Consultation</h2>

        <!-- Login Form -->
        <form action="" method="POST" class="form">
            <h3>Login</h3>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <!-- Sign Up Form -->
        <form action="" method="POST" class="form">
            <h3>Sign Up</h3>
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="signup">Sign Up</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
