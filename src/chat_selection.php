<?php
include('DBconnection.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to assign different avatar color
function getAvatarColor($id) {
    $colors = [
        '#F48FB1', // Main Pink
        '#F06292', // Hot Pink
        '#EC407A', // Strong Pink
        '#CE93D8', // Light Purple
        '#AB47BC', // Medium Purple
        '#7E57C2', // Deep Purple
        '#E91E63', // Magenta Pink
        '#BA68C8'  // Soft Purple
    ];
    return $colors[$id % count($colors)];
}

$sql = "SELECT id, email FROM users WHERE id != $user_id";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Select a User to Chat</title>
    <link rel="stylesheet" href="chat_selection.css">
</head>
<body>
    <div class="CipherWhisper">
        <h2>CipherWhisper</h2>
    </div>

    <h2>Select a user to chat with:</h2>

    <div class="user-list">
        <?php while ($row = mysqli_fetch_assoc($result)) :
            $initial = strtoupper(substr($row['email'], 0, 1));
        ?>
            <a href="chat.php?receiver_id=<?= $row['id'] ?>" class="user-card">
                <div class="avatar" style="background-color: <?= getAvatarColor($row['id']) ?>;">
                    <?= $initial ?>
                </div>
                <div class="user-info">
                    <div class="email"><?= htmlspecialchars($row['email']) ?></div>
                </div>
                <div class="arrow">→</div>
            </a>
        <?php endwhile; ?>
    </div>
</body>
</html>
