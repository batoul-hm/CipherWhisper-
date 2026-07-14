<?php
$host = 'mysql-se';      
$user = 'batoul';
$password = 'root';
$db = 'BatoulTel';

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
