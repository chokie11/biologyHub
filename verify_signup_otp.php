<?php
session_start();
include 'backend/db.php';

$otp = $_POST['otp'] ?? '';

if(empty($otp)){
    echo "Enter OTP";
    exit();
}

/* ==========================
   SIGNUP VERIFICATION
========================== */
if(isset($_SESSION['verify_id'])){

    $user_id = $_SESSION['verify_id'];

    $stmt = $conn->prepare("SELECT otp FROM students WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        echo "User not found.";
        exit();
    }

    $row = $result->fetch_assoc();

    if($row['otp'] == $otp){

        // Clear OTP
        $stmt2 = $conn->prepare("UPDATE students SET otp=NULL WHERE id=?");
        $stmt2->bind_param("i",$user_id);
        $stmt2->execute();

        $_SESSION["usrid"] = $user_id;

        unset($_SESSION['verify_id']);

        echo "success";
    }else{
        echo "Invalid or expired OTP";
    }

    exit();
}

/* ==========================
   FORGOT PASSWORD
========================== */
if(isset($_SESSION['reset_id'])){

    $user_id = $_SESSION['reset_id'];

    $stmt = $conn->prepare("SELECT otp FROM students WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0){
        echo "User not found.";
        exit();
    }

    $row = $result->fetch_assoc();

    if($row['otp'] == $otp){
        echo "reset";
    }else{
        echo "Invalid or expired OTP";
    }

    exit();
}

echo "Session expired.";