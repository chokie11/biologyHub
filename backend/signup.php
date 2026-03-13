<?php
include "db.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // ===== CHECK EMPTY =====
    if(empty($name) || empty($email) || empty($password) || empty($confirm)){
        echo "<script>
Swal.fire({
  icon: 'error',
  title: 'Oops...',
  text: 'Please fill up all fields!',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";
exit();

    }

    // ===== EMAIL VALIDATION =====
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
       echo "<script>
Swal.fire({
  icon: 'error',
  title: 'Invalid Email',
  text: 'Please enter a valid email address',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";
exit();

    }

    // ===== PASSWORD PATTERN =====
    if(!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)){
       echo "<script>
Swal.fire({
  icon: 'warning',
  title: 'Weak Password',
  html: 'Password must contain:<br>• 8 characters<br>• 1 uppercase<br>• 1 number<br>• 1 special character',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";
exit();

    }

    // ===== CONFIRM PASSWORD =====
    if($password !== $confirm){
        echo "<script>
Swal.fire({
  icon: 'error',
  title: 'Password not match',
  text: 'Confirm password must match',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";
exit();

    }

    // ===== CHECK EMAIL EXISTS =====
    $check = $conn->prepare("SELECT id FROM students WHERE email=?");
    $check->bind_param("s",$email);
    $check->execute();
    $check->store_result();

    if($check->num_rows>0){
        echo "<script>
Swal.fire({
  icon: 'info',
  title: 'Email already registered',
  text: 'Try another email or login instead',
  confirmButtonColor: '#4f46e5'
}).then(()=>{window.history.back();});
</script>";
exit();

    }

    // ===== HASH PASSWORD =====
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // ===== INSERT USER =====
    $stmt = $conn->prepare("INSERT INTO students(name,email,password) VALUES(?,?,?)");
    $stmt->bind_param("sss",$name,$email,$hashed);

    if($stmt->execute()){
       echo "<script>
Swal.fire({
  icon: 'success',
  title: 'Account Created!',
  text: 'You can now login 🎓',
  confirmButtonColor: '#4f46e5'
}).then(()=>{ window.location='index.html';});
</script>";

    }else{
        echo "Error: ".$stmt->error;
    }
}
?>
