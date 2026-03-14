<?php

include 'backend/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

$login_error = "";
$signup_error = "";
$signup_success = "";
if (isset($_POST["login"])) {

    $uname = $_POST['txtusername'] ?? '';
    $pword = $_POST['txtpassword'] ?? '';

    if (empty($uname)) {
        $login_error = "Email is required.";
    } else if (empty($pword)) {
        $login_error = "Password is required.";
    } else if ($uname == "admin@gmail.com" && $pword == "admin") {
        header("Location: /physicsHub/index.php");
        exit();
    } else {

        $sql = "SELECT * FROM students WHERE email='$uname'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($pword, $row['password'])) {
                $_SESSION["usrid"] = $row["id"];
                $id = $row["id"];
                header("Location: /biologyHub/index.php");
                exit();
            } else {
                $login_error = "Incorrect Email or Password";
            }
        } else {
            $login_error = "Incorrect Email or Password";
        }

    }
}

if (isset($_POST["create"])) {



    $fullname = $_POST['name'] ?? '';
    $uname = $_POST['email'] ?? '';
    $pword = $_POST['password'] ?? '';
    $cpword = $_POST['confirm_password'] ?? '';

    // patterns
    $emailPattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    $passPattern = "/^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if (empty($fullname)) {
        $signup_error = "Full name required.";
    } else if (empty($uname)) {
        $signup_error = "Email required.";
    } else if (!preg_match($emailPattern, $uname)) {
        $signup_error = "Invalid email format.";
    } else if (empty($pword)) {
        $signup_error = "Password required.";
    } else if (!preg_match($passPattern, $pword)) {
        $signup_error = "Password must be 8+ chars, include uppercase, number & special character.";
    } else if (empty($cpword)) {
        $signup_error = "Confirm password required.";
    } else if ($pword !== $cpword) {   // FIXED (not ===)
        $signup_error = "Passwords do not match.";
    } else {

        $check = $conn->query("SELECT * FROM students WHERE email='$uname'");
        if ($check->num_rows > 0) {
            $signup_error = "Email already registered.";
        } else {

 
            $hashed = password_hash($pword, PASSWORD_DEFAULT);

            $sql = "INSERT INTO students (name,email,password) 
        VALUES('$fullname','$uname','$hashed')";



            if ($conn->query($sql) === TRUE) {

                $user_id = $conn->insert_id;

                // generate 6 digit OTP
                $otp = rand(100000, 999999);

                // save OTP to database
                $conn->query("UPDATE students SET otp='$otp' WHERE id='$user_id'");

                // SEND EMAIL
                require 'PHPMailer/src/PHPMailer.php';
                require 'PHPMailer/src/SMTP.php';
                require 'PHPMailer/src/Exception.php';

                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'noreply30211@gmail.com'; // YOUR GMAIL
                  
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('noreply30211@gmail.com', 'PhysicsHub');
                    $mail->addAddress($uname);

                    $mail->isHTML(true);
                    $mail->Subject = 'Your Verification Code';
                    $mail->Body = "
            <h2>Email Verification</h2>
            <p>Your OTP code is:</p>
            <h1>$otp</h1>
            <p>Enter this code to verify your account.</p>
        ";

                    $mail->send();

                    // go to verify page

                    $conn->query("UPDATE students SET otp='$otp' WHERE id='$user_id'");

                    $_SESSION['verify_id'] = $user_id;
                    $_SESSION['verify_email'] = $uname;

                    $signup_success = "OTP_SENT"; // trigger modal
                    // exit();

                } catch (Exception $e) {
                    $signup_error = "Email not sent. Mail error.";
                }

            } else {
                $signup_error = "Error creating account.";
            }


        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <title>PhysicsHub</title>
    <!-- Favicons -->
    <link href="assets\img\apple-touch-icon copy.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Vendor CSS Files -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="vendor/aos/aos.css" rel="stylesheet">
    <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        #signupBtn {
            position: relative;
            transition: 0.3s ease;
        }

        #signupBtn:disabled {
            transform: scale(0.98);
        }

        .mobile-view {
            display: none;
        }


        .container {
            display: block;
        }

        /* 📱 When screen is mobile */
        @media (max-width: 768px) {

            body {
                background: linear-gradient(to right, #e2e2e2, #c9d6ff);
                height: auto;
                padding: 20px;
            }

            .container {
                display: none;
                /* Hide your animated design */
            }

            .mobile-view {
                display: flex;
                /* Show mobile version */
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            .mobile-card {
                background: #fff;
                width: 100%;
                max-width: 380px;
                padding: 30px 20px;
                border-radius: 20px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
                text-align: center;
            }

            .mobile-logo {
                width: 140px;
                margin-bottom: 20px;
            }

            .mobile-card h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }

            .mobile-card input {
                width: 100%;
                padding: 12px;
                margin-bottom: 12px;
                border-radius: 8px;
                border: none;
                background: #f1f1f1;
                font-size: 14px;
            }

            .mobile-card button {
                width: 100%;
                padding: 12px;
                border-radius: 8px;
                border: none;
                background: radial-gradient(100% 100% at 100% 0%, #6087cb 0%, #3960a2 100%);
                color: #fff;
                font-weight: 600;
                margin-top: 10px;
            }

            .mobile-toggle {
                display: flex;
                margin-bottom: 20px;
            }

            .mobile-toggle button {
                flex: 1;
                background: #eee;
                color: #333;
                margin: 0;
                border-radius: 8px;
            }

            .mobile-toggle button.active {
                background: #3960a2;
                color: #fff;
            }
        }
    </style>
</head>

<body>


    <div class="container <?php
    if (isset($_POST['create']) && $signup_error != '')
        echo 'active';
    ?>" id="container">

        <div class="form-container sign-up">
            <form action="login.php" method="POST">
                <h1>Create Account</h1>
                <input name="name" type="text" placeholder="Name">
                <input name="email" type="email" placeholder="Email">

                <input type="password" name="password" id="password" placeholder="Password">

                <!-- <div class="strength">
                    <div id="strength-bar"></div>
                </div>
                <small id="strength-text"></small>

 -->


                <input type="password" name="confirm_password" id="confirm" placeholder="Confirm Password">

                <button type="submit" name="create">
                    <span id="signupText">Sign Up</span>
                </button>



                <?php if ($signup_error != "") { ?>
                    <p class="error"><?php echo $signup_error; ?></p>
                <?php } ?>

                <?php if ($signup_success != "") { ?>
                    <p style="color:green; margin-top:10px;"><?php echo $signup_success; ?></p>
                <?php } ?>

                <?php if ($signup_success == "OTP_SENT") { ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            document.getElementById("otpModal").style.display = "flex";
                            startTimer();
                        });
                    </script>
                <?php } ?>


            </form>

        </div>

        <script>
            function togglePass(id, icon) {
                const input = document.getElementById(id);
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.add("fa-eye");
                    icon.classList.remove("fa-eye-slash");
                }
            }

            const pass = document.getElementById("password");
            const bar = document.getElementById("strength-bar");
            const text = document.getElementById("strength-text");

            pass.addEventListener("input", () => {

                let val = pass.value;
                let strength = 0;

                if (val.length >= 8) strength++;
                if (/[A-Z]/.test(val)) strength++;
                if (/[0-9]/.test(val)) strength++;
                if (/[\W]/.test(val)) strength++;

                if (strength == 1) {
                    bar.style.width = "25%";
                    bar.style.background = "red";
                    text.innerHTML = "Weak password";
                }
                else if (strength == 2) {
                    bar.style.width = "50%";
                    bar.style.background = "orange";
                    text.innerHTML = "Medium password";
                }
                else if (strength == 3) {
                    bar.style.width = "75%";
                    bar.style.background = "#0d6efd";
                    text.innerHTML = "Strong password";
                }
                else if (strength == 4) {
                    bar.style.width = "100%";
                    bar.style.background = "green";
                    text.innerHTML = "Very Strong 🔥";
                } else {
                    bar.style.width = "0%";
                    text.innerHTML = "";
                }
            });


        </script>
        <div class="form-container sign-in">
            <form action="login.php" method="POST">
                <h1>Log In</h1>

                <input type="email" name="txtusername" placeholder="Email">
                <input type="password" name="txtpassword" placeholder="Password">
                <a href="#" data-toggle="modal" data-target="#forgotModal">Forget Your Password?</a>
                <!-- <button type="submit" name="login" value="Save">Sign In</button> -->
                <button type="submit" name="login">Log In</button>

                <?php if ($login_error != "") { ?>
                    <p class="error"><?php echo $login_error; ?></p>
                <?php } ?>

            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">

                <div class="toggle-panel toggle-left">
                    <img class="aycon" src="img/biologyhublogo.png" alt="" width="240px">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Log In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <img class="aycon" src="img/biologyhublogo.png" alt="" width="240px">
                    <h1>Hello, Student!</h1>
                    <p>Register with your personal details to use all of site features</p>
                    <button class="hidden" id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FORGOT PASSWORD MODAL -->
    <div class="modal fade" id="forgotModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center">

                <h4>Reset Password</h4>
                <p>Enter your registered email</p>

                <input type="email" id="forgotEmail" class="form-control mb-3" placeholder="Enter your email">

                <button id="sendOtpBtn" onclick="sendForgotOTP()" class="btn btn-primary btn-block"
                    style="background: radial-gradient( 100% 100% at 100% 0%, #6087cb 0%, #3960a2 100% );"
                    onclick="sendForgotOTP()">
                    <span id="btnText">Send OTP</span>
                    <span id="btnLoader" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true">
                    </span>

                </button>



            </div>
        </div>
    </div>

    <script>
        function sendForgotOTP() {

            let email = $("#forgotEmail").val();
            let btn = $("#sendOtpBtn");

            if (email.trim() === "") {
                alert("Enter your email.");
                return;
            }

            // $_SESSION['reset_email'] = $email;
            // 🔒 Disable button
            btn.prop("disabled", true);

            // 🔄 Show loader
            $("#btnText").text("Sending...");
            $("#btnLoader").removeClass("d-none");

            $.post("forgot_password.php", { email: email }, function (data) {

                if (data == "success") {

                    $('#forgotModal').modal('hide');
                    $('#otpModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#otpModal').modal('show');
                    startTimer();

                } else {

                    alert(data);

                    // ❌ Re-enable button if error
                    btn.prop("disabled", false);
                    $("#btnText").text("Send OTP");
                    $("#btnLoader").addClass("d-none");
                }

            }).fail(function () {

                alert("Server error. Try again.");

                btn.prop("disabled", false);
                $("#btnText").text("Send OTP");
                $("#btnLoader").addClass("d-none");

            });
        }
    </script>



    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Your account has been successfully created.

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- OTP BOOTSTRAP MODAL -->
    <div class="modal fade" id="otpModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center">

                <h4>Email Verification</h4>
                <p>Enter 6-digit code sent to your email</p>

                <div class="d-flex justify-content-center mb-3" id="otpBoxes">
                    <input maxlength="1" class="form-control mx-1 text-center otp" style="width:45px;">
                    <input maxlength="1" class="form-control mx-1 text-center otp" style="width:45px;">
                    <input maxlength="1" class="form-control mx-1 text-center otp" style="width:45px;">
                    <input maxlength="1" class="form-control mx-1 text-center otp" style="width:45px;">
                    <input maxlength="1" class="form-control mx-1 text-center otp" style="width:45px;">
                    <input maxlength="1" class="form-control mx-1 text-center otp" style="width:45px;">
                </div>

                <button class="btn btn-primary btn-block"
                    style="background: radial-gradient( 100% 100% at 100% 0%, #6087cb 0%, #3960a2 100% );"
                    onclick="verifyOTP()">Verify Account</button>

                <p class="mt-2">Resend in <span id="time">120</span>s</p>
                <!-- <button class="btn btn-link" id="resendBtn" onclick="resendOTP()" disabled>Resend Code</button> -->
                <button class="btn btn-link" id="resendBtn" onclick="resendOTP()" disabled>
                    <span id="resendText">Resend Code</span>
                    <span id="resendLoader" class="spinner-border spinner-border-sm d-none" role="status"
                        aria-hidden="true"></span>
                </button>

            </div>
        </div>
    </div>

    <!-- ====================== -->
    <!-- 📱 MOBILE VIEW DESIGN -->
    <!-- ====================== -->

    <div class="mobile-view">

        <div class="mobile-card">

            <img src="img/biologyhublogo.png" class="mobile-logo">

            <h2>Welcome to BiologyHub</h2>

            <!-- Toggle Buttons -->
            <div class="mobile-toggle">
                <button onclick="showLogin()" id="mLoginBtn" class="active">Login</button>
                <button onclick="showSignup()" id="mSignupBtn">Sign Up</button>
            </div>

            <!-- LOGIN FORM -->
            <form method="POST" id="mobileLoginForm">
                <input type="email" name="txtusername" placeholder="Email">
                <input type="password" name="txtpassword" placeholder="Password">
                <button type="submit" name="login">Login</button>

                <?php if ($login_error != "") { ?>
                    <p class="error" style="margin-top:10px; font-size: 13px;"><?php echo $login_error; ?></p>
                <?php } ?>
            </form>

            <!-- SIGNUP FORM -->
            <form method="POST" id="mobileSignupForm" style="display:none;">
                <input type="text" name="name" placeholder="Full Name">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="confirm_password" placeholder="Confirm Password">
                <button type="submit" name="create">Create Account</button>
                <?php if ($signup_error != "") { ?>
                    <p class="error" style="margin-top:10px; font-size: 13px;"><?php echo $signup_error; ?></p>
                <?php } ?>

                <?php if ($signup_success != "") { ?>
                    <p style="color:green; margin-top:10px; font-size: 13px;"><?php echo $signup_success; ?></p>
                <?php } ?>

                <?php if ($signup_success == "OTP_SENT") { ?>
                    <script>
                        document.addEventListener("DOMContentLoaded", () => {
                            document.getElementById("otpModal").style.display = "flex";
                            startTimer();
                        });
                    </script>
                <?php } ?>

            </form>

        </div>

    </div>

    <!-- RESET PASSWORD MODAL -->
    <div class="modal fade" id="resetModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 text-center">

                <h4>Set New Password</h4>

                <input type="password" id="newPassword" class="form-control mb-2" placeholder="New Password">

                <input type="password" id="confirmNewPassword" class="form-control mb-3" placeholder="Confirm Password">

                <button class="btn btn-primary btn-block"
                    style="background: radial-gradient( 100% 100% at 100% 0%, #6087cb 0%, #3960a2 100% );"
                    onclick="resetPassword()">
                    Update Password
                </button>

            </div>
        </div>
    </div>
    <script>
        function resetPassword() {
            let pass = $("#newPassword").val();
            let cpass = $("#confirmNewPassword").val();

            $.post("update_password.php",
                { password: pass, confirm: cpass },
                function (data) {
                    if (data == "success") {
                        alert("Password updated successfully!");
                        window.location.reload();
                    } else {
                        alert(data);
                    }
                });
        }
    </script>
    <script>
        // auto move cursor
        document.querySelectorAll(".otp").forEach((box, i, arr) => {
            box.addEventListener("input", () => {
                if (box.value.length === 1 && arr[i + 1]) arr[i + 1].focus();
            });
        });

        function getOTP() {
            let code = "";
            document.querySelectorAll(".otp").forEach(e => code += e.value);
            return code;
        }

        // VERIFY
        // function verifyOTP(){
        //  let otp=getOTP();

        //  $.post("verify_signup_otp.php",{otp:otp},function(data){
        //    if(data=="success"){
        //       window.location.href = "index.php";
        //    }else{
        //      alert(data);
        //    }
        //  });
        // }

        function verifyOTP() {
            let otp = getOTP();

            $.post("verify_signup_otp.php", { otp: otp }, function (data) {

                if (data == "success") {
                    window.location.href = "index.php";
                }
                else if (data == "reset") {
                    $('#otpModal').modal('hide');
                    $('#resetModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                    $('#resetModal').modal('show');
                }
                else {
                    alert(data);
                }

            });
        }

        // TIMER
        let t = 20;
        function startTimer() {
            let timer = setInterval(() => {
                t--;
                $("#time").text(t);

                if (t <= 0) {
                    clearInterval(timer);
                    $("#resendBtn").prop("disabled", false);
                }
            }, 1000);
        }

        // RESEND
        // function resendOTP() {
        //     $.get("resend_signup_otp.php", function () {
        //         alert("OTP resent");
        //         t = 120;
        //         startTimer();
        //         $("#resendBtn").prop("disabled", true);
        //     });
        // }

        function resendOTP() {

    let btn = $("#resendBtn");

    // 🔒 Disable button immediately
    btn.prop("disabled", true);

    // 🔄 Show loader
    $("#resendText").text("Sending...");
    $("#resendLoader").removeClass("d-none");

    $.get("resend_signup_otp.php", function (data) {

        if (data.trim() === "resent") {

            t = 120;
            startTimer();

        } else {

            alert(data);

            // ❌ If error, re-enable button
            btn.prop("disabled", false);
        }

    }).fail(function () {

        alert("Server error. Try again.");
        btn.prop("disabled", false);

    }).always(function () {

        // Restore button appearance
        $("#resendText").text("Resend Code");
        $("#resendLoader").addClass("d-none");

    });
}
    </script>


    <!-- <script>
            document.querySelector(".sign-up form").addEventListener("submit", function () {

            let btn = document.getElementById("signupBtn");

            // Disable button
            btn.disabled = true;

            // Change text
            document.getElementById("signupText").innerText = "Creating...";

            // Show spinner
            document.getElementById("signupLoader").classList.remove("d-none");

        }); 
    </script> -->

    <script>
        document.querySelector(".sign-up form").addEventListener("submit", function () {

            let btn = document.getElementById("signupBtn");
            let text = document.getElementById("signupText");
            let loader = document.getElementById("signupLoader");

            // Disable button
            btn.disabled = true;
            btn.style.opacity = "0.8";
            btn.style.cursor = "not-allowed";

            // Change text
            text.innerText = "Creating Account...";

            // Show spinner
            loader.classList.remove("d-none");
        });
    </script>
    <!-- <script>
        function success() {
            $('#successModal').modal('show');
        }
    </script> -->

    <script src="assets/js/login.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showLogin() {
            document.getElementById("mobileLoginForm").style.display = "block";
            document.getElementById("mobileSignupForm").style.display = "none";

            document.getElementById("mLoginBtn").classList.add("active");
            document.getElementById("mSignupBtn").classList.remove("active");
        }

        function showSignup() {
            document.getElementById("mobileLoginForm").style.display = "none";
            document.getElementById("mobileSignupForm").style.display = "block";

            document.getElementById("mLoginBtn").classList.remove("active");
            document.getElementById("mSignupBtn").classList.add("active");
        }
    </script>

</body>

</html>

<?php if ($signup_success == "OTP_SENT") { ?>
    <script>
        $(document).ready(function () {
            $('#otpModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#otpModal').modal('show');
            startTimer();
        });
    </script>
<?php } ?>
