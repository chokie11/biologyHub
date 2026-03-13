<?php
session_start();
include "backend/db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

$email = $_POST['email'] ?? '';

if (empty($email)) {
    echo "Email required.";
    exit();
}

$result = $conn->query("SELECT * FROM students WHERE email='$email'");

if ($result->num_rows == 0) {
    echo "Email not found.";
    exit();
}

$row = $result->fetch_assoc();
$user_id = $row['id'];

$otp = rand(100000, 999999);

$conn->query("UPDATE students SET otp='$otp' WHERE id='$user_id'");

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'noreply30211@gmail.com';
    $mail->Password = 'lqiq ynok ngfh muvr';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('noreply30211@gmail.com', 'PhysicsHub');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Password Reset OTP';
    $mail->Body = "
        <h2>Password Reset</h2>
        <p>Your OTP code is:</p>
        <h1>$otp</h1>
    ";

    $mail->send();

    $_SESSION['reset_id'] = $user_id;
    $_SESSION['reset_email'] = $email;

    // $_SESSION['reset_mode'] = true;
    echo "success";

} catch (Exception $e) {
    echo "Mail error.";
}
?>