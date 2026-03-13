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

        $lesson_descriptions = [
            'The_Cell' => 'This lesson summarizes cell structure and organelles, including differences between plant and animal cells. It reviews the history of cell discovery from Hooke to Virchow and explains the three principles of cell theory. It also describes the structure and function of major organelles and highlights the role of microscopy in studying cells.',
            'Cell_cycle' => 'This lesson explains the cell cycle, which includes growth and division processes that produce new cells. It describes interphase (G1, S, G2) where the cell grows and copies its DNA, and the M phase, where division occurs. The M phase includes mitosis, producing two identical daughter cells, and meiosis, which forms gametes.',
            'Transport_Mechanisms' => 'This lesson explores how substances move across cell membranes. It covers passive transport mechanisms like diffusion and osmosis, and active transport mechanisms like endocytosis and exocytosis. It also discusses the role of transport proteins and membrane structure in facilitating these processes.'
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
            'Metabolism_respiration' => '🔥 Metabolism Respiration'
        ];

        $lesson_descriptions = [
            'Cell_Requirements' => 'This lesson explains that all cells require energy, nutrients, and waste removal to survive. It introduces autotrophs and heterotrophs and identifies chloroplasts as the site of photosynthesis in plant cells.',

            'Photosynthesis' => 'This lesson explains how photosynthesis converts light energy into chemical energy. It covers the role of chlorophyll, the balanced chemical equation, light-dependent reactions (ATP and NADPH production), and the Calvin cycle for glucose synthesis. It also discusses the environmental importance of reducing carbon dioxide levels.',

            'Metabolism_respiration' => 'This lesson describes how cells convert chemical energy into ATP through aerobic respiration. It explains glycolysis, acetyl CoA formation, the citric acid cycle, and the electron transport chain. It also compares aerobic and anaerobic respiration and highlights the role of mitochondria in energy production.'
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
        $lesson_titles = [
            'Plant_Tissues' => '🌿 Plant Cells, Tissues, and Organs',
            'Animal_Tissues' => '🧠 Animal Tissues and Organ Systems'
        ];

        $lesson_descriptions = [
            'Plant_Tissues' => 'This lesson explains how cell specialization forms plant tissues, organs, and organ systems. It identifies simple and complex permanent tissues using a microscope and examines the structure and function of roots, stems, leaves, xylem, and phloem. The lesson also discusses how plant tissues improve crop productivity and support plant survival.',

            'Animal_Tissues' => 'This lesson explores the structure and function of the four main types of animal tissues: epithelial, connective, muscle, and nervous tissues. Students compare tissues using microscopy and analyze how specialized tissues form organs and organ systems, such as cardiac muscle in the heart and ciliated epithelium in the respiratory system.'
        ];

        $learn = [
            'Explain how cell specialization forms tissues, organs, and organ systems',
            'Differentiate simple and complex plant tissues using microscopy',
            'Describe the structure and function of xylem, phloem, roots, stems, and leaves',
            'Compare the four main types of animal tissues and their functions',
            'Analyze how tissue specialization supports growth, survival, and homeostasis'
        ];

        $header = "Biology 2: Plant and Animal Tissues";

        $sub_header = "Understand how specialized cells form tissues and organ systems that support growth, transport, and survival in plants and animals.";

        $course_description = "This course examines the structure and function of plant and animal tissues. Students explore how specialized cells form tissues, organs, and organ systems that maintain homeostasis and sustain life. Through analysis, research, and presentations, learners apply biological concepts to health, environmental sustainability, and food security.";

        break;
    case '4th_quarter':
        $lesson_titles = [
            'Organ_Systems' => '🌳 Organ Systems of Human Body'
        ];

        $lesson_descriptions = [
            'Organ_Systems' => 'This lesson describes the key human body systems and their functions. It discusses the digestive system and the process of digestion from mouth to anus. It also outlines the circulatory system including the heart, blood and blood vessels, as well as the three types of circulation. Finally, it summarizes the skeletal, muscular and excretory systems and their roles in supporting movement, structure and waste removal.'
        ];

        $learn = [
            'Explain how plant root and shoot systems work together to maintain homeostasis',
            'Describe the interdependence of plant organ systems in growth and survival',
            'Identify major animal organ systems and summarize their functions',
            'Explain how organ systems work together using examples such as gas exchange and circulation',
            'Define homeostasis and analyze how feedback mechanisms regulate body conditions'
        ];

        $header = "Biology 2: Organ systems of human body";

        $sub_header = "Explore how plant and animal organ systems work together to maintain homeostasis and support life processes.";

        $course_description = "This course examines how organ systems in plants and animals function and interact to maintain homeostasis. Students analyze system interdependence, feedback mechanisms, and the effects of lifestyle choices and chronic diseases on overall health and environmental sustainability.";

        break;
    default:
        $lessons = ['The_Cell', 'Cell_cycle', 'Transport_Mechanisms'];
}
?>

<?php
// lessons included in this quarter
$lessonKeys = array_keys($lesson_titles);

// convert array to SQL string
$lessonList = "'" . implode("','", $lessonKeys) . "'";

// leaderboard query (total score per student)
$leaderboard = mysqli_query($conn, "
SELECT 
    students.name, 
    SUM(lesson_progress.quiz_score) as total_score,
    COUNT(lesson_progress.lesson_name) as quizzes_taken
FROM lesson_progress
JOIN students ON students.id = lesson_progress.student_id
WHERE lesson_progress.lesson_name IN ($lessonList)
AND lesson_progress.quiz_score IS NOT NULL
GROUP BY lesson_progress.student_id
HAVING total_score >= 0
ORDER BY total_score DESC
LIMIT 10
");

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

        .leaderboard-card {
            margin-top: 30px;
        }

        .leader-sub {
            color: #64748b;
            margin-bottom: 20px;
        }

        .leaderboard {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .leader-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8fafc;
            padding: 15px 18px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .leader-row:hover {
            transform: scale(1.02);
            background: #eef2ff;
        }

        .rank {
            font-size: 18px;
            font-weight: 700;
            color: #475569;
        }

        .student {
            flex: 1;
            margin-left: 15px;
        }

        .score {
            background: #3960a2;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
        }

        /* TOP 3 COLORS */
        .gold {
            background: linear-gradient(90deg, #fef9c3, #fde68a);
        }

        .silver {
            background: linear-gradient(90deg, #e5e7eb, #cbd5f5);
        }

        .bronze {
            background: linear-gradient(90deg, #fed7aa, #fdba74);
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


        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="#objective">Objectives</a></li>
                <li><a href="#lesson">Lessons</a></li>
                <li><a href="#leaderboard">Leaderboard</a></li>
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

<body class="index-page">




    <div class="mainContent" style="margin-top: 100px;">


        <div class="topbars">
            <div class="buttonHero"><button onclick="window.location.href='index.php'" style="cursor:pointer;">
                    < Back</button>
            </div>
            <!-- <div class="buttonHero"><button  onclick="goFull()"> ⛶ Fullscreen</button></div> -->
        </div>


        <!-- HEADER -->
        <div class="headers" id="objective">
            <h1><?php echo htmlspecialchars($header); ?></h1>
            <p>
                <?php echo htmlspecialchars($sub_header); ?>
            </p>
        </div>


        <!-- COURSE DESCR IPTION -->
        <div class="card">
            <h2>About this course</h2>
            <p>
                <?php echo htmlspecialchars($course_description); ?>
            </p>
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

        <div class="card" id="lesson">
            <h2>Cell Biology Lessons</h2>

            <div class="grid">

                <?php foreach ($lesson_titles as $lessonKey => $lessonTitle): ?>

                    <div class="lesson-box">

                        <!-- Lesson Title -->
                        <h3 onclick="window.location.href='lesson-view.php?lesson=<?php echo $lessonKey; ?>'"
                            style="cursor:pointer;">
                            <?php echo $lessonTitle; ?>
                        </h3>

                        <!-- Lesson Description -->
                        <p class="lesson-text"
                            onclick="window.location.href='lesson-view.php?lesson=<?php echo $lessonKey; ?>'"
                            style="cursor:pointer;">
                            <?php echo $lesson_descriptions[$lessonKey]; ?>
                        </p>

                        <?php
                        $lessonName = $lessonKey;

                        if (isset($progress[$lessonName])) {

                            $data = $progress[$lessonName];

                            if ($data['completed'] == 1) {
                                echo "<div class='status done'>
                                ✅ Completed <br>
                                Score: <b>" . $data['quiz_score'] . "/20</b>
                              </div>";
                            } else if ($data['slide_number'] > 0) {
                                echo "<button class='resume-btn'
                                onclick=\"window.location.href='lesson-view.php?lesson=$lessonName'\">
                                🔁 Resume Lesson
                              </button>";
                            } else {
                                echo "<button class='start-btn'
                                onclick=\"window.location.href='lesson-view.php?lesson=$lessonName'\">
                                ▶ Continue
                              </button>";
                            }

                        } else {
                            echo "<button class='start-btn'
                            onclick=\"window.location.href='lesson-view.php?lesson=$lessonName'\">
                            ▶ Start Lesson
                          </button>";
                        }
                        ?>

                    </div>

                <?php endforeach; ?>

            </div>
        </div>




        <div class="card leaderboard-card" id="leaderboard">
            <h2>🏆 Quarter Leaderboard</h2>
            <p class="leader-sub">Top students this quarter</p>

            <div class="leaderboard">

                <?php
                if (mysqli_num_rows($leaderboard) > 0):

                    $rank = 1;
                    while ($row = mysqli_fetch_assoc($leaderboard)):
                        ?>

                        <div class="leader-row 
        <?php
        if ($rank == 1)
            echo 'gold';
        elseif ($rank == 2)
            echo 'silver';
        elseif ($rank == 3)
            echo 'bronze';
        ?>">

                            <div class="rank">#<?php echo $rank; ?></div>

                            <div class="student">
                                👤 <?php echo htmlspecialchars($row['name']); ?>
                            </div>

                            <div class="score">
                                <?php echo $row['total_score']; ?> pts
                             
                                <small style="font-weight:500;">
                                    (<?php echo $row['quizzes_taken']; ?>  <?php if( $row['quizzes_taken']==1){
                                        echo "quiz";
                                    } else {
                                        echo "quizzes";
                                    } ?>  taken)
                                </small>
                            </div>

                        </div>

                        <?php
                        $rank++;
                    endwhile;

                else:
                    ?>

                    <div style="
        text-align:center;
        padding:40px 20px;
        background:#f8fafc;
        border-radius:12px;
        color:#64748b;
    ">
                        🏆 No leaderboard data yet <br>
                        <small>Be the first to complete a quiz this quarter!</small>
                    </div>

                <?php endif; ?>

            </div>
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