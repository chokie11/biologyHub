<?php
session_start();
include "backend/db.php";

if(!isset($_SESSION['reset_id'])){
    echo "Session expired.";
    exit();
}

$user_id = $_SESSION['reset_id'];
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';

if(empty($password) || empty($confirm)){
    echo "All fields required.";
    exit();
}

if($password !== $confirm){
    echo "Passwords do not match.";
    exit();
}

$hashed = password_hash($password,PASSWORD_DEFAULT);

$conn->query("UPDATE students SET password='$hashed', otp=NULL WHERE id='$user_id'");

unset($_SESSION['reset_id']);
// unset($_SESSION['reset_mode']);

echo "success";
?>