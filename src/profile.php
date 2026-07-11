<?php
include('DBconnection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get user information
$sql = "SELECT email, created_at FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="chat_selection.css">
</head>
<body>
    <div class="CipherWhisper">
        <h2>CipherWhisper🛡️</h2>
    </div>

    <h2>My Profile</h2>

    <div class="user-list" style="max-width: 400px; margin: 0 auto;">
        <div class="user-card" style="flex-direction: column; align-items: flex-start; padding: 25px;">
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Registered on:</strong> <?= date('d M Y', strtotime($user['created_at'])) ?></p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="chat.php?receiver_id=1" class="start-button" style="background-color: #64B5F6;">Start Chatting</a>
    </div>
</body>
</html>