<?php
session_start();
include "backend/db.php"; // your DB connection (if needed)
?>

<?php
$user_name = "";

if (isset($_SESSION["usrid"])) {
    $uid = $_SESSION["usrid"];

    $res = $conn->query("SELECT name FROM students WHERE id='$uid'");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $user_name = $row['name'];
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiologyHub</title>

    <!-- Vendor CSS Files -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="vendor/aos/aos.css" rel="stylesheet">
    <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    <style>
        .hero {
  position: relative;
  width: 100%;
  max-height: 500px;
  overflow: hidden;
}


/* Dark gradient overlay at the top */
.hero::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 60%;
  background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0));
}

/* Text styling */
.hero-text {
  position: absolute;
  top: 20px;
  left: 30px;
  color: white;
  z-index: 2;
}

.hero-text h1 {
  font-size: 20px;
  margin: 0;
  color: white;
  font-weight: bold;
   text-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
}

.hero-text p {
  font-size: 15px;
  margin-top: 0px;
   text-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
}

    </style>

</head>

<body class="index-page">
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="index.php" class="logo d-flex align-items-center ">
                <!-- Uncomxment the line below if you also wish to use an image logo -->
                <img src="img/biologyhublogo.png" alt="">
                <h1 class="sitename"><b>Biology</b>Hub</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#courses">Presentation</a></li>
                    <li><a href="#videos">Video Lesson</a></li>
                    <!-- <li><a href="#work-process">Quizzes</a></li> -->

                    <!-- Mobile Only Auth Buttons -->
                    <div class="mobile-login">


                        <?php if (!isset($_SESSION["usrid"])): ?>
                            <!-- NOT LOGGED IN -->
                            <li class="mobile-auth">
                                <a href="login.php" class="login-link">Login</a>
                            </li>
                            <li class="mobile-auth">
                                <a href="login.php" class="signup-link">Signup</a>
                            </li>

                        <?php else: ?>

                            <li class="mobile-auth">
                                <a href="backend/logout.php" class="login-link">Logout</a>
                            </li>



                        <?php endif; ?>


                    </div>

                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="buttonHero accountAct" id="accountAct">

                <?php if (!isset($_SESSION["usrid"])): ?>
                    <!-- NOT LOGGED IN -->
                    <button onclick="window.location.href='login.php'">Login</button>
                    <button class="signup" onclick="window.location.href='login.php'">Signup</button>

                <?php else: ?>
                    <!-- LOGGED IN -->
                    <span style="color:white;margin-right:10px;">
                        👋 Welcome, <b>
                            <?php echo htmlspecialchars($user_name); ?>
                        </b>
                    </span>

                    <a href="backend/logout.php">
                        <button>Logout</button>
                    </a>

                <?php endif; ?>

            </div>


            <!-- <button id="mode" class="theme-toggle" aria-label="Toggle dark mode">
                🌙
            </button> -->


            <script>
                const modeBtn = document.getElementById("mode");

                if (modeBtn) {
                    modeBtn.addEventListener("click", () => {
                        document.body.classList.toggle("dark-mode");

                        // Toggle icon
                        modeBtn.textContent = document.body.classList.contains("dark-mode")
                            ? "☀️"
                            : "🌙";

                        // Save preference
                        localStorage.setItem(
                            "theme",
                            document.body.classList.contains("dark-mode") ? "dark" : "light"
                        );
                    });
                }

                // Load saved theme on refresh
                document.addEventListener("DOMContentLoaded", () => {
                    const savedTheme = localStorage.getItem("theme");

                    if (savedTheme === "dark") {
                        document.body.classList.add("dark-mode");
                        if (modeBtn) modeBtn.textContent = "☀️";
                    }
                });


            </script>
        </div>
    </header>

    <section class="mumuna" id="home">
        <div class="context" data-aos="zoom-in" data-aos-delay="200">
            <h1><span>Explore</span> the World of <span>Biology</span></h1>
            <!-- <div class="typing-container" id="typing-text"> </div> -->
            <p>Learn, Explore, and Master Biology Concepts.</p>

            <div class="buttonHero">
                <button class="getStarted"> <a href="#courses">Get Started</a></button>
                <button> <a href="#courses"> Browse Courses</a></button>
            </div>
        </div>
        <!-- <div class="itsuraKu" data-aos="zoom-in" data-aos-delay="200">
      <img src="img/myPic.png" alt="">
    </div> -->
    </section>


    <div class="mainContent">
        <!-- <section class="top-cards" id="first">
            <div class="card-box" data-aos="zoom-in" data-aos-delay="200">
                <div class="icon">▶️</div>

                <div>
                    <h3>Video Lessons</h3>
                    <p>Engaging tutorials and lectures</p>
                </div>
            </div>

            <div class="card-box" data-aos="zoom-in" data-aos-delay="200">
                <div class="icon">⚛️</div>
                <div>
                    <h3>Interactive Simulations</h3>
                    <p>Hands on physics experiments</p>
                </div>
            </div>

            <div class="card-box" data-aos="zoom-in" data-aos-delay="200">
                <div class="icon">📝</div>
                <div>
                    <h3>Practice Quizzes</h3>
                    <p>Test your knowledge</p>
                </div>
            </div>
        </section> -->

        <section class="popular-courses" id="courses"  data-aos="fade-up">
            <div class="container section-title">
                <h1>Biology Presentations</h1>
            </div><!-- End Section Title -->

            <div class="course-container">

                <!-- CARD 1 -->
                <div class="course-card" >
                    <!-- <img src="img/course1.jpg" alt="Mechanics"> -->

                    <div class="hero">
                        <img src="img/course1.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Cell Biology</h1>
                            <p>Biology 1: 1st Quarter</p>
                        </div>
                    </div>

                    <div class="course-content">
                        <div class="course-footer">
                            <span>3 Lessons</span>
                            <button onclick="window.location.href='lesson.php?quarter=1st_quarter'">View Course</button>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="course-card" >
                    <div class="hero">
                        <img src="img/course2.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Cell Metabolism</h1>
                            <p>Biology 1: 2nd Quarter</p>
                        </div>
                    </div>
                    <div class="course-content">

                        <div class="course-footer">
                            <span>3 Lessons</span>
                            <button onclick="window.location.href='lesson.php?quarter=2nd_quarter'">View Course</button>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="course-card">
                    <div class="hero">
                        <img src="img/course3.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Plants and Animal Tissues background</h1>
                            <p>Biology 2: 3rd Quarter</p>
                        </div>
                    </div>
                    <div class="course-content">

                        <div class="course-footer">
                            <span>2 Lessons</span>
                            <button  onclick="window.location.href='lesson.php?quarter=3rd_quarter'">View Course</button>
                        </div>
                    </div>
                </div>

                <!-- CARD 4 -->
                <div class="course-card" >
                     <div class="hero">
                        <img src="img/course4.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Organ Systems</h1>
                            <p>Biology 2: 4th Quarter</p>
                        </div>
                    </div>
                    <div class="course-content">

                        <div class="course-footer">
                            <span>1 Lesson</span>
                            <button  onclick="window.location.href='lesson.php?quarter=4th_quarter'">View Course</button>
                        </div>
                    </div>
                </div>

            </div>
        </section>



          <section class="popular-courses" id="videos">
            <div class="container section-title" data-aos="fade-up">
                <h1>Biology Video Lessons</h1>
            </div><!-- End Section Title -->

            <div class="course-container">

                <!-- CARD 1 -->
                <div class="course-card" data-aos="fade-up">
                    <!-- <img src="img/course1.jpg" alt="Mechanics"> -->

                    <div class="hero">
                        <img src="img/course5.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Cell Biology</h1>
                            <p>Biology 1: 1st Quarter</p>
                        </div>
                    </div>

                    <div class="course-content">

                        <div class="course-footer">
                            <span>3 Lessons</span>
                            <button onclick="window.location.href='video_lesson.php?quarter=1st_quarter'">View Course</button>
                        </div>
                    </div>
                </div>

                <!-- CARD 2 -->
                <div class="course-card" data-aos="fade-up">
                    <div class="hero">
                        <img src="img/course6.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Cell Metabolism</h1>
                            <p>Biology 1: 2nd Quarter</p>
                        </div>
                    </div>
                    <div class="course-content">

                        <div class="course-footer">
                            <span>3 Lessons</span>
                            <button onclick="window.location.href='video_lesson.php?quarter=2nd_quarter'">View Course</button>
                        </div>
                    </div>
                </div>

                <!-- CARD 3 -->
                <div class="course-card" data-aos="fade-up">
                    <div class="hero">
                        <img src="img/course7.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Plants and Animal Tissues background</h1>
                            <p>Biology 2: 3rd Quarter</p>
                        </div>
                    </div>
                    <div class="course-content">

                        <div class="course-footer">
                            <span>2 Lessons</span>
                            <button>View Course</button>
                        </div>
                    </div>
                </div>

                <!-- CARD 4 -->
                <div class="course-card" data-aos="fade-up">
                     <div class="hero">
                        <img src="img/course8.jpg" alt="Hero Image">
                        <div class="hero-text">
                            <h1>Organ Systems</h1>
                            <p>Biology 2: 4th Quarter</p>
                        </div>
                    </div>
                    <div class="course-content">

                        <div class="course-footer">
                            <span>1 Lesson</span>
                            <button>View Course</button>
                        </div>
                    </div>
                </div>

            </div>
        </section>


    </div>






    <!-- Floating Chat Button -->
    <!-- <div id="chat-toggle">💬</div>

    <div id="chat-widget" class="hidden">
        <div class="chat-header">
            <span>AI Chatbot</span>
            <button onclick="toggleChat()">✕</button>
        </div>

        <div id="chat-messages"></div>

        <div class="chat-input">
            <input id="userInput" type="text" placeholder="Ask me something..."
                onkeydown="if(event.key==='Enter') sendMessage()" />
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script src="chatbot.js"></script>
 -->





    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>





    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init(); // Initialize AOS
    </script>


    <!-- Vendor JS Files -->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="vendor/php-email-form/validate.js"></script> -->
    <script src="vendor/aos/aos.js"></script>
    <script src="vendor/glightbox/js/glightbox.min.js"></script>
    <script src="vendor/swiper/swiper-bundle.min.js"></script>
    <script src="vendor/waypoints/noframework.waypoints.js"></script>
    <script src="vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="vendor/isotope-layout/isotope.pkgd.min.js"></script>

    <!-- Main JS File -->
    <script src="script.js"></script>
</body>

</html>