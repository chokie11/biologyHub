<?php
session_start();
include "db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($email) || empty($password)){
        echo "<script>alert('Fill all fields');window.history.back();</script>";
        exit();
    }

    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Invalid email');window.history.back();</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT id,name,password FROM students WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows==1){
        $row=$result->fetch_assoc();

        if(password_verify($password,$row['password'])){
            $_SESSION['student_id']=$row['id'];
            $_SESSION['name']=$row['name'];

            header("Location: dashboard.php");
            exit();
        }else{
           echo "<script>
Swal.fire({
  icon: 'error',
  title: 'Wrong Password',
  text: 'Check your password and try again',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";

        }
    }else{
       echo "<script>
Swal.fire({
  icon: 'question',
  title: 'Account not found',
  text: 'Please sign up first',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";

    }
}
?>

