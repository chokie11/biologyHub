<?php
session_start();
include 'backend/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

if (isset($_SESSION['verify_id'])) {
    // SIGNUP OTP
    $id = $_SESSION['verify_id'];
    $email = $_SESSION['verify_email'];
}
elseif (isset($_SESSION['reset_id'])) {
    // FORGOT PASSWORD OTP
    $id = $_SESSION['reset_id'];
    $email = $_SESSION['reset_email'];
}
else {
    exit("Session expired");
}

$otp = rand(100000,999999);

$conn->query("UPDATE students SET otp='$otp' WHERE id='$id'");

$mail = new PHPMailer(true);

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
$mail->Subject = 'Your new OTP';
$mail->Body = "<h1>$otp</h1><p>Expires in 2 minutes</p>";

$mail->send();

echo "resent";
?>