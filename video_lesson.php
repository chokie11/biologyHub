<?php
session_start();
include "backend/db.php"; // your DB connection (if needed)

// if user not logged in → redirect to login
if (!isset($_SESSION["usrid"])) {
    header("Location: login.php");
    exit();
}

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

<?php


// get all progress of student
$progress = [];
$res = mysqli_query($conn, "SELECT * FROM lesson_progress WHERE student_id='$uid'");
while ($row = mysqli_fetch_assoc($res)) {
    $progress[$row['lesson_name']] = $row;
}

$quarter = $_GET['quarter'] ?? '1st2_quarter'; // default to 1st quarter if not set

switch ($quarter) {
    case '1st_quarter':

        $lesson_titles = [
            'The_Cell' => '🔬 The Cell',
            'Cell_cycle' => '♻️ Cell Cycle (Mitosis and Meiosis)',
            'Transport_Mechanisms' => '🚚 Transport Mechanisms'
        ];

        $iframe = [
            'The_Cell' => '<iframe src="https://www.youtube.com/embed/URUJD5NEXC8"
        title="YouTube video"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>',

            'Cell_cycle' => '<iframe src="https://www.youtube.com/embed/QVCjdNxJreE"
        title="YouTube video"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen></iframe>',

            'Transport_Mechanisms' => '<iframe src="https://www.youtube.com/embed/Ptmlvtei8hw?si=VOL90Zykbs5m_6Wg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin"
        allowfullscreen></iframe>'
        ];


        $learn = [
            'Explain the development and significance of the modern cell theory',
            'Differentiate prokaryotic and eukaryotic cells based on structure and function',
            'Describe the phases of the cell cycle, including mitosis and meiosis',
            'Explain how passive and active transport maintain cellular homeostasis',
            'Analyze how abnormalities in cell processes contribute to diseases'
        ];

        $header = "Biology 1: Cell Biology";
        $sub_header = "Explore the scientific foundations of cell theory, the cell cycle, and transport mechanisms that explain the structure, function, growth, and diversity of living organisms.";
        $course_description = "This course explores the foundations of cell biology, including cell theory, cell structure, the cell cycle, and transport mechanisms. Students examine how cells grow, reproduce, and maintain homeostasis, and how cellular processes relate to health, disease, and the diversity of life.";

        break;
    case '2nd_quarter':
        $lesson_titles = [
            'Cell_Requirements' => '🌱 Cell Requirements',
            'Photosynthesis' => '☀️ Photosynthesis',
            'Metabolism_respiration' => '🔥 Cellular Respiration'
        ];

        $iframe = [
            'Cell_Requirements' => '<iframe src="https://www.youtube.com/embed/p0gJazl83mI?si=TNDc3Grtf5r1sHGU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" 
        allowfullscreen></iframe>',

            'Photosynthesis' => '<iframe src="https://www.youtube.com/embed/BsVIPnIeYFs?si=1zrvl5_UL20cPPHI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" 
        allowfullscreen></iframe>',

            'Metabolism_respiration' => '<iframe src="https://www.youtube.com/embed/eJ9Zjc-jdys?si=0h_ZCwBewfgo5LxP" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" 
        allowfullscreen></iframe>'
        ];

        $learn = [
            'Identify the role of chloroplasts and pigments in photosynthesis',
            'Explain the light-dependent and light-independent reactions of photosynthesis',
            'Describe the stages of aerobic respiration and ATP production',
            'Differentiate aerobic and anaerobic respiration',
            'Explain how photosynthesis and respiration sustain life and ecosystem stability'
        ];

        $header = "Biology 1: Cell Metabolism";

        $sub_header = "Understand how cells obtain, transform, and use energy through photosynthesis and cellular respiration.";

        $course_description = "This course explores how cells require energy and nutrients to survive. Students examine the processes of photosynthesis and cellular respiration, including ATP production, and analyze how these energy transformations sustain life and maintain ecosystem balance.";

        break;

    case '3rd_quarter':
        $lessons = ['Human_Biology', 'Plant_Biology', 'Microbiology'];
        break;
    case '4th_quarter':
        $lessons = ['Biotechnology', 'Neuroscience', 'Immunology'];
        break;
    default:
        $lessons = ['The_Cell', 'Cell_cycle', 'Transport_Mechanisms'];
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
    <link rel="stylesheet" href="assets/css/lesson.css">

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>


    <style>
        .start-btn {
            background: #3960a2;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .resume-btn {
            background: #f59e0b;
            color: #fff;
            border: none;
            padding: 10px 18px;
            border-radius: 8px;
            margin-top: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .status.done {
            margin-top: 10px;
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 10px;
            font-weight: 600;
        }

        .topbars {

            margin-bottom: 20px;
        }



        iframe {
            width: 100%;
            height: 350px;
            border-radius: 12px;
        }
    </style>
</head>


<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

        <a href="index.php" class="logo d-flex align-items-center ">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <img src="img/biologyhublogo.png" alt="">
            <h1 class="sitename"><b>Biology</b>Hub</h1>
        </a>


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

<body class="index-page">




    <div class="mainContent" style="margin-top: 100px;">


        <div class="topbars">
            <div class="buttonHero"><button onclick="window.location.href='index.php'" style="cursor:pointer;">
                    < Back</button>
            </div>
            <!-- <div class="buttonHero"><button  onclick="goFull()"> ⛶ Fullscreen</button></div> -->
        </div>


        <!-- HEADER -->
        <div class="headers">
            <h1><?php echo htmlspecialchars($header); ?></h1>
            <p>
                <?php echo htmlspecialchars($sub_header); ?>
            </p>
        </div>


        <!-- COURSE DESCRIPTION -->
        <div class="card">
            <h2>About this course</h2>
            <p>
                <?php echo htmlspecialchars($course_description); ?>
            </p>
        </div>


        <div class="card">
            <h2>Cell Biology Lessons</h2>

            <div class="grid">

                <?php foreach ($lesson_titles as $lessonKey => $lessonTitle): ?>
                    <div class="lesson-box">
                        <h3><?php echo $lessonTitle; ?></h3>

                        <?php
                        if (isset($iframe[$lessonKey])) {
                            echo $iframe[$lessonKey];
                        } else {
                            echo "<p>No video yet</p>";
                        }
                        ?>
                    </div>
                <?php endforeach; ?>


            </div>
        </div>

        <!-- OBJECTIVES -->
        <div class="card">
            <h2>What students will learn</h2>
            <ul style="margin-left:18px; color:#475569; line-height:1.8;">
                <?php foreach ($learn as $learns): ?>
                    <li><?php echo $learns; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>






    </div>


    <script>
        document.querySelectorAll('.read-more-btn').forEach(button => {
            button.addEventListener('click', function () {
                const text = this.previousElementSibling;
                text.classList.toggle('expanded');

                if (text.classList.contains('expanded')) {
                    this.textContent = "Read Less";
                } else {
                    this.textContent = "Read More";
                }
            });
        });
    </script>









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