<?php
include('DBconnection.php');
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['receiver_id'])) {
    header("Location: login.php");
    exit();
}

$sender_id = $_SESSION['user_id'];
$receiver_id = intval($_GET['receiver_id']);

$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $receiver_id);
$stmt->execute();
$result = $stmt->get_result();
$receiver_email = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['email'] : 'Unknown User';
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat with <?= htmlspecialchars($receiver_email) ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="sender_id" content="<?= $sender_id ?>">
    <meta name="receiver_id" content="<?= $receiver_id ?>">
    <link rel="stylesheet" href="chat.css">
    <script src="chat.js" defer></script>
</head>
<body>
    <header class="header">
        <nav>
            <ul class="nav-links">
                <li><a href="index.html">🏠 Home</a></li>
                <li><a href="chat_selection.php">👥 User Selection</a></li>
                <li><a href="profile.php">👤 My Profile</a></li>
                <li><a href="logout.php">🚪 Log Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="chat-container">
        <?php 
             $short_name = explode('@', $receiver_email)[0];
        ?>
        <h2>
             💬 Chat with 
             <span style="color: #F48FB1;"><?= htmlspecialchars($short_name) ?></span>
        </h2>
        <div id="chat-box"></div>

        <div class="key-section">
            <div class="key-header">
                <strong>🔑 Upload Your Private Key</strong>
            </div>
            <div class="key-input-group">
                <input type="file" id="private-key-file" accept=".pem" class="file-input">
                <button type="button" onclick="loadPrivateKeyFromFile()" class="load-key-btn">
                     Load Private Key
                </button>
            </div>
        </div>

        <form id="send-message-form" onsubmit="event.preventDefault(); sendMessage();">
            <input type="text" id="message" placeholder="Type a message" required>
            <button type="submit">Send</button>
        </form>
    </div>
</body>
</html>
