<?php
session_start();
require_once("../config/db.php");
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password=MD5('$password')";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $_SESSION['user'] = $username;
    header("Location: ../views/dashboard.php");
} else {
    echo "Invalid login.";
}
?>