<?php
include('DBconnection.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);


        if (!empty($user['locked_until']) && strtotime($user['locked_until']) > time()) {
            $error = "Your account is locked due to too many failed attempts. Please try again later.";
        } else {
            $hashed_password = $user['password'];

            if (password_verify($password, $hashed_password)) {

                $reset_sql = "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = " . $user['id'];
                mysqli_query($conn, $reset_sql);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                header("Location: chat_selection.php");
                exit();
            } else {

                $new_attempts = $user['failed_attempts'] + 1;
                $update_sql = "UPDATE users SET failed_attempts = $new_attempts, last_failed_attempt = NOW() WHERE id = " . $user['id'];
                mysqli_query($conn, $update_sql);

                if ($new_attempts >= 5) {

                    $lock_sql = "UPDATE users SET locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = " . $user['id'];
                    mysqli_query($conn, $lock_sql);
                    $error = "Too many failed attempts. Your account has been locked for 15 minutes.";
                } else {
                    $remaining = 5 - $new_attempts;
                    $error = "Invalid password! You have $remaining attempts left.";
                }
            }
        }
    } else {
        $error = "No account found with that email!";
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CipherWhisper - Login</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="login-container">
        <h2>Login to CipherWhisper</h2>

        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                    required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <p class="register-text">Don't have an account? <a href="register.html">Register</a></p>
    </div>
</body>

</html>