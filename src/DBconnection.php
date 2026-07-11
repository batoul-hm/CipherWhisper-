<?php

$host = 'mysql';
$user = 'batoul';
$password = 'root';
$db = 'BatoulTel';

$conn = mysqli_connect($host, $user, $password, $db);

if (!$conn) {
    echo "connection error --> " . mysqli_connect_error();
}
?>
