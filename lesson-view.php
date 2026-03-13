<?php
session_start();
include "backend/db.php";

if (!isset($_SESSION["usrid"])) {
    header("Location: login.php");
    exit();
}

$user_name = "";

if (isset($_SESSION["usrid"])) {
    $uid = $_SESSION["usrid"];

    $res = $conn->query("SELECT name FROM students WHERE id='$uid'");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $user_name = $row['name'];
    }
}

$student_id = $_SESSION["usrid"];
$lesson = $_GET['lesson'] ?? 'The_Cell';


$folder = "assets/slides/$lesson/";

/* get all images */
$slides = glob($folder . "*.{jpg,png,jpeg}", GLOB_BRACE);


$totalSlides = count($slides);


/* GET SAVED SLIDE */
$resumeSlide = 1;

$q = $conn->query("SELECT slide_number, quiz_score 
FROM lesson_progress 
WHERE student_id='$student_id' AND lesson_name='$lesson'");

$alreadyScored = false;

if ($q && $q->num_rows > 0) {
    $data = $q->fetch_assoc();
    $resumeSlide = (int) $data['slide_number'];

    if ($data['quiz_score'] !== null) {
        $alreadyScored = true;
    }
}

$lessonTitle = '';
$lessonDesc = '';
$quarter = '';

$qua;

switch ($lesson) {
    case 'The_Cell':
        $lessonTitle = 'The Cell';
        $lessonDesc = 'This lesson covers cell structure and organelles, differences between plant and animal cells, the history of cell discovery from Hooke to Virchow, and the three principles of cell theory. It also highlights the role of microscopy in studying cells.';
        $quarter = 'Biology 1: Quarter 1';

        $qua = '1st_quarter';

        break;

    case 'Cell_cycle':
        $lessonTitle = 'Cell Cycle (Mitosis and Meiosis)';
        $lessonDesc = 'This lesson explains the cell cycle, which includes growth and division processes that produce new cells. It describes interphase (G1, S, G2) where the cell grows and copies its DNA, and the M phase, where division occurs. The M phase includes mitosis, producing identical daughter cells for growth and repair, and meiosis, which creates genetically diverse gametes for sexual reproduction.';
        $quarter = 'Biology 1: Quarter 1';

        $qua = '1st_quarter';
        break;

    case 'Transport_Mechanisms':
        $lessonTitle = 'Transport Mechanisms';
        $lessonDesc = 'This lesson explores how substances move across cell membranes. It covers passive transport mechanisms like diffusion and osmosis, and active transport mechanisms like endocytosis and exocytosis. It also discusses the role of transport proteins and membrane structure in facilitating these processes.';
        $quarter = 'Biology 1: Quarter 1';
        $qua = '1st_quarter';
        break;


    case 'Cell_Requirements':
        $lessonTitle = 'Cell Cycle (Mitosis and Meiosis)';
        $lessonDesc = 'This lesson explains the cell cycle, which includes growth and division processes that produce new cells. It describes interphase (G1, S, G2) where the cell grows and copies its DNA, and the M phase, where division occurs. The M phase includes mitosis, producing identical daughter cells for growth and repair, and meiosis, which creates genetically diverse gametes for sexual reproduction.';
        $quarter = 'Biology 1: Quarter 2';
        $qua = '2nd_quarter';
        break;

    case 'Photosynthesis':
        $lessonTitle = 'Metabolism and Photosynthesis';
        $lessonDesc = 'This lesson explores metabolism, the set of chemical reactions that occur within living cells to maintain life. It focuses on photosynthesis as an important anabolic process in which light energy is converted into chemical energy stored in glucose. Students will learn the balanced chemical equation of photosynthesis, examine the light-dependent and light-independent reactions, and understand how this process helps reduce carbon dioxide levels in the environment while sustaining life on Earth.';
        $quarter = 'Biology 1: Quarter 2';
        $qua = '2nd_quarter';
        break;

    case 'Metabolism_respiration':
        $lessonTitle = 'Metabolism and Cellular Respiration';
        $lessonDesc = 'This lesson describes how cells convert chemical energy into ATP through aerobic respiration. It explains glycolysis, acetyl CoA formation, the citric acid cycle, and the electron transport chain. It also compares aerobic and anaerobic respiration and highlights the role of mitochondria in energy production.';
        $quarter = 'Biology 1: Quarter 2';
        $qua = '2nd_quarter';
        break;

    case 'Plant_Tissues':
        $lessonTitle = 'Plant Tissues and Organs';
        $lessonDesc = 'This lesson explains how cell specialization forms plant tissues, organs, and organ systems. It identifies simple and complex permanent tissues using a microscope and examines the structure and function of roots, stems, leaves, xylem, and phloem. The lesson also discusses how plant tissues improve crop productivity and support plant survival.';
        $quarter = 'Biology 1: Quarter 3';

        $qua = '3rd_quarter';
        break;

    case 'Animal_Tissues':
        $lessonTitle = 'Animal Tissues and Organs';
        $lessonDesc = 'This lesson explores the structure and function of the four main types of animal tissues: epithelial, connective, muscle, and nervous tissues. Students compare tissues using microscopy and analyze how specialized tissues form organs and organ systems, such as cardiac muscle in the heart and ciliated epithelium in the respiratory system.';
        $quarter = 'Biology 1: Quarter 3';
        $qua = '3rd_quarter';
        break;

    case 'Organ_Systems':
        $lessonTitle = 'Organ systems of human body';
        $lessonDesc = 'This lesson describes the key human body systems and their functions. It discusses the digestive system and the process of digestion from mouth to anus. It also outlines the circulatory system including the heart, blood and blood vessels, as well as the three types of circulation. Finally, it summarizes the skeletal, muscular and excretory systems and their roles in supporting movement, structure and waste removal.';
        $quarter = 'Biology 1: Quarter 4';
        $qua = '4th_quarter';
        break;




    // add more lessons here

    default:
        $lessonTitle = 'Unknown Lesson';
        $lessonDesc = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Viewer</title>

    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/lesson-view.css">

    <style>
        /* From Uiverse.io by adamgiebl */
        .buttonHero a {
            color: white;
        }

        .buttonHero button {
            font-size: 15px;
            display: inline-block;
            outline: 0;
            border: 0;
            cursor: pointer;
            will-change: box-shadow, transform;
            background: radial-gradient(100% 100% at 100% 0%, #4970b3 0%, #3960a2 100%);
            box-shadow: 0px 0.01em 0.01em rgb(45 35 66 / 40%), 0px 0.3em 0.7em -0.01em rgb(45 35 66 / 30%), inset 0px -0.01em 0px rgb(58 65 111 / 50%);
            padding: 0 1em;
            border-radius: 0.3em;
            color: #fff;
            height: 2.2em;
            text-shadow: 0 1px 0 rgb(0 0 0 / 40%);
            transition: box-shadow 0.15s ease, transform 0.15s ease;
            font-weight: 500;
        }


        .buttonHero button.signup {
            background: white;
            color: #3960a2;
        }


        .buttonHero button:hover {
            box-shadow: 0px 0.1em 0.2em rgb(45 35 66 / 40%), 0px 0.4em 0.7em -0.1em rgb(45 35 66 / 30%), inset 0px -0.1em 0px #254275;
            transform: translateY(-0.1em);
        }

        .buttonHero button:active {
            box-shadow: inset 0px 0.1em 0.6em #4970b3;
            transform: translateY(0em);
        }

        .buttonHero button.getStarted:hover {
            box-shadow: 0px 0.1em 0.2em rgb(45 35 66 / 40%), 0px 0.4em 0.7em -0.1em rgb(45 35 66 / 30%), inset 0px -0.1em 0px #8b3e04;

        }


        /* top bar */
        .topbar {
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #37517e;
            margin-top: 70px;
            background-color: transparent
        }

        /* viewer */
        .viewer {
            max-width: 1000px;
            margin: 30px auto;
        }

        /* slide */
        .carousel-item img {
            height: 520px;
            object-fit: contain;
            background: black;
            border-radius: 12px;
        }

        /* progress */
        .progress {
            height: 8px;
            background: #48566c;
            margin-bottom: 15px;
            color: black;
        }

        .progress-bar {
            background: linear-gradient(90deg, #22c55e, #4ade80);
        }

        /* controls */
        .controlBar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .fullBtn {
            background: #37517e;
            border: none;
            color: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
        }

        .quizBox {
            display: none;
            text-align: center;
            padding: 30px;
        }

        .controlBar {
            color: #37517e;
        }

        .lesson-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;

            padding: 25px 35px;
            margin: 0 auto 30px auto;
            max-width: 1200px;

            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);

            border-radius: 16px;
            /* border-left: 6px solid #47b2e4; */
            /* matches accent-color */
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);

            transition: 0.3s ease;
            margin-top: 15px;
        }

        .lesson-header:hover {
            transform: translateY(-3px);
        }


        .lesson-text {
            max-width: 800px;
        }

        .lesson-tag {
            display: inline-block;
            font-size: 12px;
            font-weight: 600;
            color: #2e7d32;
            background: #e8f5e9;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 6px;
        }

        .lesson-text h5 {
            font-size: 26px;
            font-weight: 600;
            margin: 5px 0 10px 0;
            color: var(--heading-color);
        }

        .lesson-text p {
            font-size: 15px;
            line-height: 1.7;
            color: #555;
            max-width: 850px;
        }


        .fullBtn {
            padding: 12px 22px;
            font-size: 14px;
            font-weight: 600;

            border-radius: 12px;
            border: none;
            cursor: pointer;

            background: linear-gradient(135deg, #47b2e4, #37517e);
            color: white;

            box-shadow: 0 8px 25px rgba(55, 81, 126, 0.35);
            transition: all 0.25s ease;
        }

        .fullBtn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(55, 81, 126, 0.45);
        }

        .carousel-item img {
            height: 520px;
            object-fit: contain;

            background: #0f172a;
            /* dark elegant background */
            /* border-radius: 16px;  */
            padding: 15px;

            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
        }

        .viewer {
            max-width: 1100px;
            margin: 40px auto;
            margin-top: -30px;
        }

        .progress {
            height: 10px;
            background: #e2e8f0;
            border-radius: 20px;
            overflow: hidden;
        }

        body {
            padding-top: 90px;
            /* space for fixed header */
        }

        .topbars {
            display: flex;
            justify-content: space-between;
            padding: 25px 43px;
            margin: 0 auto 30px auto;
            max-width: 1200px;

        }

        .quiz-card {
            max-width: 700px;
            margin: 40px auto;
            background: white;
            border-radius: 18px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            color: #1e293b;
            animation: fadeIn .5s ease;
        }

        .quiz-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .quiz-body h4 {
            margin-bottom: 20px;
            font-weight: 600;
        }

        .answers button {
            width: 100%;
            margin-bottom: 12px;
            padding: 14px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            cursor: pointer;
            font-weight: 500;
            transition: .2s;
        }

        .answers button:hover {
            background: #eef2ff;
            border-color: #6366f1;
        }

        .answers button.correct {
            background: #22c55e;
            color: white;
            border: none;
        }

        .answers button.wrong {
            background: #ef4444;
            color: white;
            border: none;
        }

        .nextBtn {
            margin-top: 15px;
            background: #37517e;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
        }

        .resultBox {
            text-align: center;
        }

        .finishBtn {
            background: #22c55e;
            color: white;
            border: none;
            padding: 14px 22px;
            border-radius: 12px;
        }


        /* IDENTIFICATION STYLE */
        .id-wrapper {
            margin-top: 10px;
        }

        .id-input {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            background: #f8fafc;
            margin-bottom: 12px;
            font-size: 15px;
            transition: .2s;
        }

        .id-input:focus {
            outline: none;
            border-color: #6366f1;
            background: #eef2ff;
        }


        .id-feedback {
            margin-top: 12px;
            font-weight: 600;
        }

        .id-feedback.correct {
            color: #22c55e;
        }

        .id-feedback.wrong {
            color: #ef4444;
        }
    </style>
</head>





<body>


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

    <div class="topbars">
        <div class="buttonHero"><button onclick="window.history.back()" style="cursor:pointer;">
                < Back</button>
        </div>
        <div class="buttonHero"><button onclick="goFull()"> ⛶ Fullscreen</button></div>
    </div>

    <div class="viewer">

        <div class="controlBar">
            <div id="slideCount">Slide 1 / <?php echo $totalSlides; ?></div>
        </div>

        <div class="progress">
            <div class="progress-bar" id="progressBar" style="width:0%"></div>
        </div>

        <div id="pptCarousel" class="carousel slide" data-bs-ride="false">

            <div class="carousel-inner">

                <?php
                $active = "active";
                foreach ($slides as $slide) {
                    ?>
                    <div class="carousel-item <?php echo $active; ?>">
                        <img src="<?php echo $slide; ?>" class="d-block w-100">
                    </div>
                    <?php
                    $active = ""; // only first is active
                }
                ?>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#pptCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#pptCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>

        <div class="lesson-header">
            <div class="lesson-text">
                <span class="lesson-tag"><?php echo $quarter ?></span>
                <h5><?php echo $lessonTitle; ?></h5>
                <p>
                    <?php echo $lessonDesc; ?>
                </p>
            </div>

            <!-- <button class="fullBtn" onclick="goFull()">
            ⛶ Fullscreen
        </button> -->
        </div>


        <!-- MODERN QUIZ -->
        <div class="quizBox" id="quizBox">

            <div class="quiz-card">

                <div class="quiz-header">
                    <h3>🧠 Cell Biology Quiz</h3>
                    <p id="qCounter">Question 1 of 5</p>
                </div>

                <div class="quiz-body">

                    <h4 id="question">Loading...</h4>

                    <div class="answers" id="answers"></div>

                    <button class="nextBtn" onclick="nextQuestion()">Next</button>

                </div>

                <div class="resultBox" id="resultBox" style="display:none;">
                    <h2>🎉 Quiz Completed!</h2>
                    <p id="finalScore"></p>

                    <button class="finishBtn" onclick="finishQuiz()">Done ✔</button>

                </div>

            </div>
        </div>


    </div>


    <?php
    /* QUIZ QUESTIONS PER LESSON */
    $quizData = [];

    switch ($lesson) {

        case 'The_Cell':
            $quizData = [

                // =========================
                // I. IDENTIFICATION (1-5)
                // =========================
                [
                    "type" => "id",
                    "q" => "Identify the scientist who first coined the term 'cell.'",
                    "answer" => "Robert Hooke"
                ],
                [
                    "type" => "id",
                    "q" => "Name the organelle known as the 'powerhouse of the cell.'",
                    "answer" => "Mitochondrion"
                ],
                [
                    "type" => "id",
                    "q" => "What fluid inside the nucleus contains enzymes and nucleotides?",
                    "answer" => "Nucleoplasm"
                ],
                [
                    "type" => "id",
                    "q" => "Identify the organelle responsible for protein modification and shipping.",
                    "answer" => "Golgi apparatus"
                ],
                [
                    "type" => "id",
                    "q" => "What structure provides mechanical support and maintains cell shape?",
                    "answer" => "Cytoskeleton"
                ],

                // =========================
                // II. TRUE OR FALSE (6-9)
                // =========================
                [
                    "type" => "tf",
                    "q" => "Animal cells contain chloroplasts.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "The cell theory states that cells come from other cells.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Lysosomes are found only in plant cells.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "The nucleus controls cellular activities.",
                    "correct" => true
                ],
                  [
                    "type" => "tf",
                    "q" => "When they are less numerous and longer, they are called flagella.",
                    "correct" => true
                ],


                // =========================
                // III. MULTIPLE CHOICE (10-14)
                // =========================
                [
                    "type" => "mc",
                    "q" => "Which organelle makes lipids and detoxifies chemicals?",
                    "a" => ["Lysosome", "Smooth ER", "Mitochondrion", "Ribosome"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Which cell part stores nutrients and water?",
                    "a" => ["Golgi body", "Vacuole", "Centriole", "Chloroplast"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "In microscopy, the ability to distinguish two close objects is called:",
                    "a" => ["Resolution", "Magnification", "Contrast", "Focus"],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "Which filament is part of the cytoskeleton?",
                    "a" => ["Microfilaments", "Ribosomes", "DNA strands", "Nucleolus"],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "Which organelle contains hydrolytic enzymes for digestion?",
                    "a" => ["Chloroplast", "Mitochondrion", "Lysosome", "Peroxisome"],
                    "correct" => 2
                ],

                // =========================
                // IV. MULTIPLE CHOICE (15-20) - Converted from Short Answer
                // =========================
                [
                    "type" => "mc",
                    "q" => "Which of the following is a difference between plant and animal cells?",
                    "a" => [
                        "Plant cells have cell walls; animal cells do not",
                        "Animal cells have chloroplasts; plant cells do not",
                        "Plant cells lack a nucleus",
                        "Animal cells have cell walls"
                    ],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "What does a plastid do in a plant cell?",
                    "a" => [
                        "Produces energy through respiration",
                        "Stores food or pigments",
                        "Breaks down waste materials",
                        "Controls the nucleus"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Which best defines the cell membrane?",
                    "a" => [
                        "A rigid outer layer found only in plants",
                        "A selectively permeable barrier that regulates entry and exit of substances",
                        "The structure that produces proteins",
                        "A storage sac for nutrients"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "What structure houses the cell's DNA?",
                    "a" => [
                        "Ribosome",
                        "Golgi apparatus",
                        "Nucleus",
                        "Cytoplasm"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Which component helps in cell movement?",
                    "a" => [
                        "Flagella",
                        "Vacuole",
                        "Nucleolus",
                        "Cell wall"
                    ],
                    "correct" => 0
                ]

            ];

            break;


        case 'Cell_cycle':
            $quizData = [

                // =========================
                // I. IDENTIFICATION (1-5)
                // =========================
                [
                    "type" => "id",
                    "q" => "What are the three phases of interphase?",
                    "answer" => "G1, S, G2"
                ],
                [
                    "type" => "id",
                    "q" => "Name the phase in mitosis where chromosomes line up at the cell equator.",
                    "answer" => "Metaphase"
                ],
                [
                    "type" => "id",
                    "q" => "What process produces four haploid cells?",
                    "answer" => "Meiosis"
                ],
                [
                    "type" => "id",
                    "q" => "What is the longest phase of the cell cycle?",
                    "answer" => "Interphase"
                ],
                [
                    "type" => "id",
                    "q" => "What phase immediately follows interphase in the cell cycle?",
                    "answer" => "Mitosis"
                ],

                // =========================
                // II. TRUE OR FALSE (6-9)
                // =========================
                [
                    "type" => "tf",
                    "q" => "Mitosis results in two genetically identical daughter cells.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Meiosis includes two rounds of cell division.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "DNA replication occurs in G2 phase.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "The cell cycle is a linear pathway, not a cycle.",
                    "correct" => false
                ],

                // =========================
                // III. MULTIPLE CHOICE (10-20)
                // =========================
                [
                    "type" => "mc",
                    "q" => "Which of the following is NOT part of interphase?",
                    "a" => ["G1", "S", "Cytokinesis", "G2"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "In which phase do sister chromatids separate?",
                    "a" => ["Metaphase", "Anaphase", "Prophase", "Telophase"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Meiosis I separates:",
                    "a" => ["Homologous chromosomes", "Sister chromatids", "DNA strands", "Centrosomes"],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "The purpose of the cell cycle includes:",
                    "a" => ["Repair of tissues", "Growth", "Genetic replication", "All of the above"],
                    "correct" => 3
                ],
                [
                    "type" => "mc",
                    "q" => "During which phase do chromosomes condense and spindle fibers form?",
                    "a" => ["Interphase", "Prophase", "Anaphase", "Cytokinesis"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Why do cells undergo the cell cycle?",
                    "a" => ["To produce energy only", "For growth and tissue repair", "To stop cell division", "To destroy DNA"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "What is the main difference between mitosis and meiosis?",
                    "a" => [
                        "Mitosis produces four cells; meiosis produces two",
                        "Mitosis produces identical cells; meiosis produces genetically different cells",
                        "Meiosis occurs in body cells; mitosis occurs in sex cells",
                        "There is no difference"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "What happens during the S phase?",
                    "a" => ["The cell divides", "DNA is replicated", "Chromosomes separate", "The nucleus disappears"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "What does 'haploid' mean?",
                    "a" => ["Two sets of chromosomes", "One complete set of chromosomes", "No chromosomes", "Multiple nuclei"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "One event that occurs during telophase is:",
                    "a" => ["Chromosomes line up in the middle", "Sister chromatids separate", "Nuclear membrane reforms", "DNA replicates"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "How many cells result from mitosis?",
                    "a" => ["One", "Two", "Three", "Four"],
                    "correct" => 1
                ]

            ];

            break;

        case 'Transport_Mechanisms':
            $quizData = [

                // =========================
                // I. IDENTIFICATION (1-5)
                // =========================
                [
                    "type" => "id",
                    "q" => "What model describes the structure of the plasma membrane?",
                    "answer" => "Fluid Mosaic Model"
                ],
                [
                    "type" => "id",
                    "q" => "Name the process by which water moves across a membrane.",
                    "answer" => "Osmosis"
                ],
                [
                    "type" => "id",
                    "q" => "What type of transport moves substances against a concentration gradient?",
                    "answer" => "Active transport"
                ],
                [
                    "type" => "id",
                    "q" => "What is endocytosis?",
                    "answer" => "The process of taking materials into the cell using vesicles"
                ],
                [
                    "type" => "id",
                    "q" => "What category of proteins assist in moving substances across membranes without energy?",
                    "answer" => "Transport proteins"
                ],

                // =========================
                // II. TRUE OR FALSE (6-9)
                // =========================
                [
                    "type" => "tf",
                    "q" => "Diffusion requires energy.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "Facilitated diffusion uses a channel or carrier protein.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Phagocytosis brings large particles into the cell.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Uniporters move two different molecules in the same direction.",
                    "correct" => false
                ],

                // =========================
                // III. MULTIPLE CHOICE (10-14)
                // =========================
                [
                    "type" => "mc",
                    "q" => "Which of these is a form of passive transport?",
                    "a" => ["Sodium-potassium pump", "Exocytosis", "Osmosis", "Phagocytosis"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Active transport requires:",
                    "a" => ["ATP", "Gradient only", "Vesicles only", "Oxygen"],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "A symporter moves:",
                    "a" => [
                        "One molecule at a time",
                        "Two molecules in the same direction",
                        "Two molecules in opposite directions",
                        "Water only"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Osmosis differs from diffusion because it specifically moves:",
                    "a" => ["Solute", "Water", "Energy", "Proteins"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Which would increase the rate of diffusion?",
                    "a" => [
                        "Decrease concentration gradient",
                        "Increase temperature",
                        "Lower solvent density",
                        "Use ATP"
                    ],
                    "correct" => 1
                ],

                // =========================
                // IV. MULTIPLE CHOICE (15-20) - Converted from Short Answer
                // =========================
                [
                    "type" => "mc",
                    "q" => "What does 'selectively permeable' mean?",
                    "a" => [
                        "Allows all substances to pass freely",
                        "Blocks all substances",
                        "Allows some substances to pass while restricting others",
                        "Requires ATP to function"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Which is a real-world example of diffusion?",
                    "a" => [
                        "Perfume spreading in a room",
                        "Water boiling",
                        "A ball rolling downhill",
                        "Charging a battery"
                    ],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "Receptor-mediated endocytosis is best described as:",
                    "a" => [
                        "Random intake of liquids",
                        "Uptake of specific molecules using receptor proteins",
                        "Release of materials outside the cell",
                        "Movement of water across membrane"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "What is the role of membrane proteins in transport?",
                    "a" => [
                        "They produce ATP",
                        "They store DNA",
                        "They help move substances across the membrane",
                        "They digest waste"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Which happens to a cell in a hypotonic solution?",
                    "a" => [
                        "Cell shrinks",
                        "Cell remains unchanged",
                        "Cell swells and may burst",
                        "Cell loses nucleus"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Which are two types of bulk transport?",
                    "a" => [
                        "Diffusion and osmosis",
                        "Active and passive transport",
                        "Endocytosis and exocytosis",
                        "Uniport and symport"
                    ],
                    "correct" => 2
                ]

            ];

            break;


        case 'Cell_Requirements':
            $quizData = [

                // I. IDENTIFICATION (1-5)
                ["type" => "id", "q" => "What are the four most abundant elements in living things?", "answer" => "Carbon, Hydrogen, Oxygen, Nitrogen"],
                ["type" => "id", "q" => "Which macromolecule provides nitrogen to cells?", "answer" => "Proteins"],
                ["type" => "id", "q" => "Which element from water helps with cellular respiration?", "answer" => "Oxygen"],
                ["type" => "id", "q" => "Name one ion that salts provide for cells.", "answer" => "Sodium"],
                ["type" => "id", "q" => "What element is found in DNA and energy-storing molecules?", "answer" => "Phosphorus"],

                // II. TRUE OR FALSE (6-9)
                ["type" => "tf", "q" => "Carbon, hydrogen, oxygen, and nitrogen are essential elements for cells.", "correct" => true],
                ["type" => "tf", "q" => "Carbohydrates provide phosphorus in cells.", "correct" => false],
                ["type" => "tf", "q" => "Proteins can contain sulfur.", "correct" => true],
                ["type" => "tf", "q" => "Water contributes both hydrogen and oxygen to a cell.", "correct" => true],
                ["type" => "tf", "q" => "Autotrophic cells harness light energy via photosynthesis", "correct" => true],

                // III. MULTIPLE CHOICE (10-14)
                ["type" => "mc", "q" => "Cells get essential elements from:", "a" => ["Only macromolecules", "The environment", "Solid minerals only", "Light energy"], "correct" => 1],
                ["type" => "mc", "q" => "Inorganic phosphate is especially important for:", "a" => ["Protein folding", "DNA and energy molecules", "Cell membrane integrity", "Photosynthesis"], "correct" => 1],
                ["type" => "mc", "q" => "Iron ions from salts help:", "a" => ["Stabilize enzymes", "Store ATP", "Form DNA", "Create glucose"], "correct" => 0],
                ["type" => "mc", "q" => "Carbohydrates and lipids mainly provide:", "a" => ["Nitrogen", "Carbon, hydrogen, oxygen", "Sulfur", "Calcium"], "correct" => 1],
                ["type" => "mc", "q" => "Which macromolecule contains nitrogen?", "a" => ["Proteins", "Carbohydrates","Lipids", "Glucose"], "correct" => 0],

                // IV. MULTIPLE CHOICE (15-20)
                ["type" => "mc", "q" => "Why do cells need nutrients from their environment?", "a" => ["To get essential elements for growth and function", "To create oxygen", "To stop metabolism", "To remove DNA"], "correct" => 0],
                ["type" => "mc", "q" => "Why is phosphorus critical for cells?", "a" => ["It forms ATP and DNA", "It makes glucose", "It builds proteins only", "It replaces oxygen"], "correct" => 0],
                ["type" => "mc", "q" => "What role does water play in supplying elements to cells?", "a" => ["Provides hydrogen and oxygen", "Stores DNA", "Creates ATP directly", "Forms ribosomes"], "correct" => 0],
                ["type" => "mc", "q" => "Which are essential elements other than C, H, O, N?", "a" => ["Glucose and ATP", "Phosphorus and Sulfur", "Lipids and Proteins", "Oxygen and Nitrogen"], "correct" => 1],
                ["type" => "mc", "q" => "Why are ions important for cellular function?", "a" => ["They help enzyme activity and signaling", "They create glucose", "They replace ATP", "They form membranes"], "correct" => 0]

            ];

            break;

        case 'Photosynthesis':  //last 20 done
            $quizData = [

                // I. IDENTIFICATION (1-5)
                ["type" => "id", "q" => "What is the main sugar produced by photosynthesis?", "answer" => "Glucose"],
                ["type" => "id", "q" => "Which gas is released during photosynthesis?", "answer" => "Oxygen"],
                ["type" => "id", "q" => "What two reactants are required for photosynthesis?", "answer" => "Carbon dioxide and water"],
                ["type" => "id", "q" => "Where in the plant does photosynthesis mostly occur?", "answer" => "Leaves"],
                ["type" => "id", "q" => "What molecule stores energy in cells?", "answer" => "ATP"],

                // II. TRUE OR FALSE (6-9)
                ["type" => "tf", "q" => "Photosynthesis captures sunlight energy and stores it in glucose.", "correct" => true],
                ["type" => "tf", "q" => "Cellular respiration makes energy from glucose and oxygen.", "correct" => true],
                ["type" => "tf", "q" => "Cellular respiration produces oxygen as a waste product.", "correct" => false],
                 ["type" => "tf", "q" => "Catabolism is the breakdown of complex molecules into simpler ones", "correct" => true],
                ["type" => "tf", "q" => "Photosynthesis and cellular respiration form a cycle in ecosystems.", "correct" => true],

                // III. MULTIPLE CHOICE (10-14)
                ["type" => "mc", "q" => "Photosynthesis uses which of the following as inputs?", "a" => ["Oxygen and glucose", "Carbon dioxide, water, sunlight", "ATP and carbon dioxide", "Glucose and oxygen"], "correct" => 1],
                ["type" => "mc", "q" => "The main purpose of cellular respiration is to:", "a" => ["Produce oxygen", "Create ATP", "Store glucose", "Build DNA"], "correct" => 1],
                ["type" => "mc", "q" => "The equation for photosynthesis includes:", "a" => ["CO2 + H2O → O2 + glucose", "glucose → CO2 + H2O", "O2 + glucose → CO2", "ATP + CO2 → glucose"], "correct" => 0],
                ["type" => "mc", "q" => "Cellular respiration takes place mostly in:", "a" => ["Chloroplasts", "Mitochondria", "Nucleus", "Cytoskeleton"], "correct" => 1],
                ["type" => "mc", "q" => "Which macronutrients can also enter cellular respiration after digestion?", "a" => ["Vitamins", "Fats and proteins", "Water only", "Minerals"], "correct" => 1],

                // IV. MULTIPLE CHOICE (15-20)
                ["type" => "mc", "q" => "Which represents the summary equation of photosynthesis?", "a" => ["6CO2 + 6H2O → C6H12O6 + 6O2", "C6H12O6 → CO2 + H2O", "O2 + ATP → glucose", "Glucose → ATP"], "correct" => 0],
                ["type" => "mc", "q" => "Which represents the summary equation of cellular respiration?", "a" => ["C6H12O6 + O2 → CO2 + H2O + ATP", "CO2 + H2O → glucose", "ATP → glucose", "O2 → CO2"], "correct" => 0],
                ["type" => "mc", "q" => "Why is oxygen essential in cellular respiration?", "a" => ["It is the final electron acceptor", "It creates glucose", "It stores ATP", "It forms DNA"], "correct" => 0],
                ["type" => "mc", "q" => "How is energy from photosynthesis used by animals?", "a" => ["By consuming plants and breaking down glucose", "By breathing oxygen only", "By storing sunlight", "By absorbing minerals"], "correct" => 0],
                ["type" => "mc", "q" => "What happens to starch in plants when a seed sprouts?", "a" => ["Converted into glucose for energy", "Turns into oxygen", "Forms DNA", "Becomes water"], "correct" => 0]

            ];

            break;

        case 'Metabolism_respiration':
            $quizData = [

                // I. IDENTIFICATION (1-5)
                ["type" => "id", "q" => "What is the main energy-carrying molecule produced by cellular respiration?", "answer" => "ATP"],
                ["type" => "id", "q" => "Name the three main stages of aerobic cellular respiration.", "answer" => "Glycolysis, Krebs cycle, Electron transport chain"],
                ["type" => "id", "q" => "Where does glycolysis occur in the cell?", "answer" => "Cytoplasm"],
                ["type" => "id", "q" => "What molecule enters the Krebs cycle after glycolysis?", "answer" => "Acetyl-CoA"],
                ["type" => "id", "q" => "What final electron acceptor is used in the electron transport chain?", "answer" => "Oxygen"],

                // II. TRUE OR FALSE (6-9)
                ["type" => "tf", "q" => "Cellular respiration produces ATP for cells.", "correct" => true],
                ["type" => "tf", "q" => "Only glucose can be used in cellular respiration.", "correct" => false],
                ["type" => "tf", "q" => "Cellular respiration occurs partially in mitochondria.", "correct" => true],
                ["type" => "tf", "q" => "More ATP is produced in oxidative phosphorylation than in glycolysis.", "correct" => true],
                ["type" => "tf", "q" => "Lipogenesis is the formation of fat.", "correct" => true],

                // III. MULTIPLE CHOICE (10-14)
                ["type" => "mc", "q" => "Glycolysis produces ATP in the:", "a" => ["Mitochondria", "Cytoplasm", "Endoplasmic reticulum", "Nucleus"], "correct" => 1],
                ["type" => "mc", "q" => "The Krebs cycle is also called the:", "a" => ["Calvin cycle", "Electron transport chain", "Citric acid cycle", "Glycolysis"], "correct" => 2],
                ["type" => "mc", "q" => "The electron transport chain produces ATP by:", "a" => ["Breaking glucose in half", "Pumping protons to make ATP", "Making glucose", "Creating DNA"], "correct" => 1],
                ["type" => "mc", "q" => "Fats and proteins can enter cellular respiration by becoming:", "a" => ["ATP directly", "Glucose only", "Components that enter Krebs cycle", "Carbon dioxide already"], "correct" => 2],
                ["type" => "mc", "q" => "Anaerobic respiration occurs when:", "a" => ["Oxygen is abundant", "No glucose is present", "There is no oxygen", "ATP is not needed"], "correct" => 2],

                // IV. MULTIPLE CHOICE (15-20)
                ["type" => "mc", "q" => "Why do cells need ATP?", "a" => ["To power cellular processes", "To create oxygen", "To form DNA only", "To stop metabolism"], "correct" => 0],
                ["type" => "mc", "q" => "Main difference between aerobic and anaerobic respiration?", "a" => ["Aerobic uses oxygen; anaerobic does not", "Anaerobic produces more ATP", "Aerobic occurs in cytoplasm only", "No difference"], "correct" => 0],
                ["type" => "mc", "q" => "How many net ATP are produced in glycolysis?", "a" => ["2", "4", "6", "8"], "correct" => 0],
                ["type" => "mc", "q" => "What happens to pyruvate without oxygen?", "a" => ["Undergoes fermentation", "Enters Krebs cycle", "Becomes ATP directly", "Forms DNA"], "correct" => 0],
                ["type" => "mc", "q" => "Which is a type of fermentation?", "a" => ["Lactic acid fermentation", "Photosynthesis", "Oxidative phosphorylation", "Calvin cycle"], "correct" => 0]

            ];

            break;

        case 'Plant_Tissues':
            $quizData = [

                // =========================
                // I. IDENTIFICATION (1-5)
                // =========================
                [
                    "type" => "id",
                    "q" => "Name the plant cells that have thin walls and large central vacuoles and are often photosynthetic.",
                    "answer" => "Parenchyma cells"
                ],
                [
                    "type" => "id",
                    "q" => "What type of cells provide support in non-woody stems and leaf petioles?",
                    "answer" => "Collenchyma cells"
                ],
                [
                    "type" => "id",
                    "q" => "Which plant cells have thick, lignin-rich secondary walls and provide the main support in woody plants?",
                    "answer" => "Sclerenchyma cells"
                ],
                [
                    "type" => "id",
                    "q" => "What vascular tissue conducts water and minerals from roots to leaves?",
                    "answer" => "Xylem"
                ],
                [
                    "type" => "id",
                    "q" => "Name the two types of conducting cells found within xylem.",
                    "answer" => "Tracheids and vessel elements"
                ],

                // =========================
                // II. TRUE OR FALSE (6-9)
                // =========================
                [
                    "type" => "tf",
                    "q" => "Phloem cells are alive when they do their job.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Ground tissue mainly functions in support, storage, and photosynthesis.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Dermal tissue forms the inner layers of a plant’s vascular system.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "Apical meristems are responsible for elongation of stems and roots.",
                    "correct" => true
                ],

                // =========================
                // III. MULTIPLE CHOICE (10-14)
                // =========================
                [
                    "type" => "mc",
                    "q" => "Which plant tissue system functions like a plumbing system?",
                    "a" => ["Ground", "Dermal", "Vascular", "Meristematic"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Mesophyll cells are specialized for:",
                    "a" => ["Protection", "Photosynthesis", "Support only", "Water transport"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Sieve-tube members are part of which tissue?",
                    "a" => ["Xylem", "Parenchyma", "Phloem", "Dermal"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "The waxy layer that reduces water loss on leaves is called the:",
                    "a" => ["Endodermis", "Cuticle", "Cortex", "Periderm"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "The shoot apical meristem mainly contributes to:",
                    "a" => ["Root diameter increase", "Leaf differentiation", "Stem elongation", "Flower opening"],
                    "correct" => 2
                ],

                // =========================
                // IV. MULTIPLE CHOICE (15-20)
                // =========================
                [
                    "type" => "mc",
                    "q" => "What is the main function of sclerenchyma cells?",
                    "a" => [
                        "Photosynthesis",
                        "Long-distance transport",
                        "Flexible support in young tissues",
                        "Rigid structural support and protection"
                    ],
                    "correct" => 3
                ],
                [
                    "type" => "mc",
                    "q" => "What are meristems?",
                    "a" => [
                        "Mature transport tissues",
                        "Regions of actively dividing cells",
                        "Dead support cells",
                        "Photosynthetic tissues"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Why do parenchyma cells in leaves contain many chloroplasts?",
                    "a" => [
                        "For protection",
                        "For storage",
                        "For photosynthesis",
                        "For water transport"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "What is the primary role of ground tissue?",
                    "a" => [
                        "Transport of sugars",
                        "Protection from water loss",
                        "Support, storage, and photosynthesis",
                        "Production of seeds"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "How do vessel elements differ from tracheids?",
                    "a" => [
                        "Vessel elements are shorter and have perforation plates",
                        "Tracheids are wider and have perforation plates",
                        "Vessel elements are alive at maturity",
                        "Tracheids conduct food"
                    ],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "What is the main function of epidermal guard cells?",
                    "a" => [
                        "Produce chlorophyll",
                        "Control gas exchange by opening and closing stomata",
                        "Transport minerals",
                        "Provide structural support"
                    ],
                    "correct" => 1
                ]

            ];

            break;

        case 'Animal_Tissues':
            $quizData = [

                // =========================
                // I. IDENTIFICATION (1-5)
                // =========================
                [
                    "type" => "id",
                    "q" => "What do we call a group of cells that work together to perform a specific function?",
                    "answer" => "Tissue"
                ],
                [
                    "type" => "id",
                    "q" => "Name one plant tissue system that replaces the epidermis in older stems.",
                    "answer" => "Periderm"
                ],
                [
                    "type" => "id",
                    "q" => "What plant tissue transports organic materials such as carbohydrates and amino acids?",
                    "answer" => "Phloem"
                ],
                [
                    "type" => "id",
                    "q" => "List one type of animal tissue involved in movement.",
                    "answer" => "Muscle tissue"
                ],
                [
                    "type" => "id",
                    "q" => "Which animal tissue covers external and internal body surfaces?",
                    "answer" => "Epithelial tissue"
                ],

                // =========================
                // II. TRUE OR FALSE (6-9)
                // =========================
                [
                    "type" => "tf",
                    "q" => "Most plant tissues are living because plants need high energy to move.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "Connective tissue is found in animals.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Meristematic tissue in plants can divide throughout the plant’s life.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "Nervous tissue is a type of animal tissue.",
                    "correct" => true
                ],

                // =========================
                // III. MULTIPLE CHOICE (10-14)
                // =========================
                [
                    "type" => "mc",
                    "q" => "Simple permanent tissue in plants is composed of:",
                    "a" => ["More than one cell type", "Only one cell type", "Dead cells only", "Sieve elements"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Muscle tissue in animals is specialized for:",
                    "a" => ["Transporting nutrients", "Creating hormones", "Contraction and movement", "Covering body surfaces"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Dermal tissue in plants primarily functions to:",
                    "a" => ["Transport water", "Protect the plant body", "Store food", "Sense stimuli"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Which of these is NOT an animal tissue type?",
                    "a" => ["Connective", "Xylem", "Muscle", "Nervous"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Lateral meristems are responsible for:",
                    "a" => ["Lengthening roots", "Increase in plant girth", "Photosynthesis", "Sugar transport"],
                    "correct" => 1
                ],

                // =========================
                // IV. MULTIPLE CHOICE (15-19)
                // =========================
                [
                    "type" => "mc",
                    "q" => "What is the difference between meristematic and permanent tissue?",
                    "a" => [
                        "Meristematic tissue divides; permanent tissue is specialized and does not divide",
                        "Permanent tissue divides; meristematic does not",
                        "Both divide equally",
                        "Both are dead tissues"
                    ],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "One function of connective tissue in animals is:",
                    "a" => [
                        "Covering surfaces",
                        "Transmitting impulses",
                        "Supporting and binding other tissues",
                        "Photosynthesis"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Epithelial tissue is specialized for:",
                    "a" => [
                        "Contraction",
                        "Protection, absorption, and secretion",
                        "Support only",
                        "Hormone production only"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Plant tissues are often described as providing structural strength because:",
                    "a" => [
                        "They contain lignin and thick cell walls",
                        "They move constantly",
                        "They produce hormones",
                        "They contain muscles"
                    ],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "Which is an example of a simple permanent tissue?",
                    "a" => ["Xylem", "Phloem", "Parenchyma", "Periderm"],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "Which animal tissue type is responsible for transmitting electrical impulses?",
                    "a" => [
                        "Muscle tissue",
                        "Epithelial tissue",
                        "Connective tissue",
                        "Nervous tissue"
                    ],
                    "correct" => 3
                ]

            ];

            break;


        case 'Organ_Systems':
            $quizData = [

                // =========================
                // I. IDENTIFICATION (1-5)
                // =========================
                [
                    "type" => "id",
                    "q" => "Identify the organ system responsible for transporting oxygen and nutrients throughout the body.",
                    "answer" => "Circulatory system"
                ],
                [
                    "type" => "id",
                    "q" => "What organ system removes metabolic wastes and regulates fluid balance?",
                    "answer" => "Urinary system"
                ],
                [
                    "type" => "id",
                    "q" => "Name the system that produces hormones and regulates body activities.",
                    "answer" => "Endocrine system"
                ],
                [
                    "type" => "id",
                    "q" => "Which organ system protects the body from pathogens and disease?",
                    "answer" => "Immune system"
                ],
                [
                    "type" => "id",
                    "q" => "Identify the primary organ of the digestive system where nutrient absorption begins.",
                    "answer" => "Small intestine"
                ],

                // =========================
                // II. TRUE OR FALSE (6-9)
                // =========================
                [
                    "type" => "tf",
                    "q" => "The skeletal system stores minerals and produces blood cells.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "The nervous system sends chemical messages through hormones.",
                    "correct" => false
                ],
                [
                    "type" => "tf",
                    "q" => "The respiratory system exchanges gases between the body and the environment.",
                    "correct" => true
                ],
                [
                    "type" => "tf",
                    "q" => "The integumentary system includes the skin, nails, and hair.",
                    "correct" => true
                ],

                // =========================
                // III. MULTIPLE CHOICE (10-14)
                // =========================
                [
                    "type" => "mc",
                    "q" => "Which organ is part of the circulatory system?",
                    "a" => ["Lung", "Heart", "Stomach", "Brain"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "The urinary system includes:",
                    "a" => ["Heart and blood vessels", "Kidneys and bladder", "Lungs and trachea", "Liver and pancreas"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "The endocrine system primarily uses:",
                    "a" => ["Nerves", "Hormones", "Muscles", "Blood cells"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "The digestive system begins at the:",
                    "a" => ["Esophagus", "Mouth", "Small intestine", "Stomach"],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "Which organ system helps with movement and posture?",
                    "a" => ["Nervous", "Skeletal", "Muscular", "Circulatory"],
                    "correct" => 2
                ],

                // =========================
                // IV. MULTIPLE CHOICE (15-20)
                // =========================
                [
                    "type" => "mc",
                    "q" => "The major function of the respiratory system is:",
                    "a" => [
                        "Pump blood",
                        "Exchange oxygen and carbon dioxide",
                        "Digest food",
                        "Produce hormones"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "One role of the lymphatic system is:",
                    "a" => [
                        "Transport oxygen",
                        "Fight infection and return tissue fluid to blood",
                        "Produce digestive enzymes",
                        "Control muscles"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "The skeletal system is important for protection because it:",
                    "a" => [
                        "Produces hormones",
                        "Covers the skin",
                        "Protects vital organs like the brain and heart",
                        "Pumps blood"
                    ],
                    "correct" => 2
                ],
                [
                    "type" => "mc",
                    "q" => "The reproductive system functions to:",
                    "a" => [
                        "Maintain posture",
                        "Produce offspring",
                        "Exchange gases",
                        "Filter blood"
                    ],
                    "correct" => 1
                ],
                [
                    "type" => "mc",
                    "q" => "How do the nervous and endocrine systems differ?",
                    "a" => [
                        "Nervous system uses electrical impulses; endocrine uses hormones",
                        "Both use hormones only",
                        "Both use nerves only",
                        "Endocrine system works faster than nervous system"
                    ],
                    "correct" => 0
                ],
                [
                    "type" => "mc",
                    "q" => "An example of how the muscular system works with another system is:",
                    "a" => [
                        "Muscles work with the skeletal system to produce movement",
                        "Muscles produce hormones",
                        "Muscles digest food",
                        "Muscles filter blood"
                    ],
                    "correct" => 0
                ]

            ];

            break;

        default:
            $quizData = [];
    }
    ?>



    <script>
        const quiz = <?php echo json_encode($quizData); ?>;

        let qIndex = 0;
        let score = 0;
        let answered = false;

        loadQuestion();

        function loadQuestion() {

            let q = quiz[qIndex];
            answered = false;

            document.getElementById("question").innerText = q.q;
            document.getElementById("qCounter").innerText =
                "Question " + (qIndex + 1) + " of " + quiz.length;

            let html = "";

            // MULTIPLE CHOICE
            if (q.type === "mc") {
                q.a.forEach((ans, i) => {
                    html += `<button onclick="selectAnswer(${i},this)">${ans}</button>`;
                });
            }

            // TRUE OR FALSE
            else if (q.type === "tf") {
                html += `
            <button onclick="selectTF(true,this)">True</button>
            <button onclick="selectTF(false,this)">False</button>
        `;
            }

            // IDENTIFICATION
            else if (q.type === "id") {
                html += `
        <div class="id-wrapper">
            <input type="text" id="idAnswer" 
                placeholder="Type your answer..."
                class="id-input">
            
            <button class="id-submit" onclick="checkID(this)">
                Submit Answer
            </button>

            <div id="idFeedback" class="id-feedback"></div>
        </div>
    `;
            }

            document.getElementById("answers").innerHTML = html;
        }

        function selectAnswer(i, btn) {
            if (answered) return;

            answered = true;

            let correct = quiz[qIndex].correct;
            let buttons = document.querySelectorAll("#answers button");

            buttons.forEach(b => b.disabled = true);

            if (i == correct) {
                btn.classList.add("correct");
                score++;
            } else {
                btn.classList.add("wrong");
                buttons[correct].classList.add("correct");
            }
        }

        function selectTF(value, btn) {

            if (answered) return;
            answered = true;

            let correct = quiz[qIndex].correct;

            let buttons = document.querySelectorAll("#answers button");
            buttons.forEach(b => b.disabled = true);

            if (value === correct) {
                btn.classList.add("correct");
                score++;
            } else {
                btn.classList.add("wrong");
            }
        }

        function checkID(btn) {

            if (answered) return;

            let input = document.getElementById("idAnswer");
            let feedback = document.getElementById("idFeedback");

            let userAnswer = input.value.trim().toLowerCase();
            let correctAnswer = quiz[qIndex].answer.toLowerCase();

            if (userAnswer === "") {
                feedback.innerHTML = "Please type an answer first 🙂";
                feedback.className = "id-feedback wrong";
                return;
            }

            answered = true;

            input.disabled = true;
            btn.disabled = true;

            if (userAnswer === correctAnswer) {

                score++;

                input.style.borderColor = "#22c55e";
                input.style.background = "#dcfce7";

                feedback.innerHTML = "✅ Correct!";
                feedback.className = "id-feedback correct";

            } else {

                input.style.borderColor = "#ef4444";
                input.style.background = "#fee2e2";

                feedback.innerHTML = "❌ Correct answer: <b>" + quiz[qIndex].answer + "</b>";
                feedback.className = "id-feedback wrong";
            }
        }

        function nextQuestion() {

            if (!answered) {
                alert("Please select an answer first 🙂");
                return;
            }

            answered = false;
            qIndex++;

            if (qIndex >= quiz.length) {
                showResult();
                return;
            }

            loadQuestion();
        }


        function showResult() {
            document.querySelector(".quiz-body").style.display = "none";
            document.getElementById("resultBox").style.display = "block";

            document.getElementById("finalScore").innerHTML =
                "You scored <b>" + score + " / " + quiz.length + "</b>";

            // progress = 100% after quiz
            document.getElementById("progressBar").style.width = "100%";
        }



        /* SAVE TO DATABASE */
        function finishQuiz() {

            fetch("backend/save_quiz.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `lesson=<?php echo $lesson; ?>&score=${score}`
            })
                .then(r => r.text())
                .then(res => {

                    document.getElementById("finalScore").innerHTML += `
<br><br>
<span style="color:#22c55e;font-weight:600;font-size:18px">
✔ Score saved! Lesson completed
</span>
`;

                    // redirect ONLY after clicking done
                    setTimeout(() => {
                        window.location.href = "lesson.php?quarter=<?php echo $qua; ?>";
                    }, 1200);

                });
        }


    </script>


    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        const carousel = document.querySelector('#pptCarousel');
        const bsCarousel = new bootstrap.Carousel(carousel, { interval: false });

        /* GO TO LAST SAVED SLIDE */
        setTimeout(() => {
            bsCarousel.to(currentSlide - 1);
            updateUI();
        }, 300);

        /* WHEN SLIDE CHANGES */
        carousel.addEventListener('slid.bs.carousel', function () {
            let active = document.querySelector(".carousel-item.active");
            currentSlide = [...active.parentNode.children].indexOf(active) + 1;
            updateUI();
        });

        let alreadyScored = <?php echo $alreadyScored ? 'true' : 'false'; ?>;

        function updateUI() {
            document.getElementById("slideCount").innerText =
                "Slide " + currentSlide + " / " + totalSlides;

            /* progress for slides only (90%) */
            let slidePercent = (currentSlide / totalSlides) * 90;

            document.getElementById("progressBar").style.width = slidePercent + "%";

            saveProgress();

            /* IF LAST SLIDE */
            if (currentSlide === totalSlides && !alreadyScored) {

                setTimeout(() => {

                    document.getElementById("quizBox").style.display = "block";

                    document.getElementById("quizBox").scrollIntoView({
                        behavior: "smooth"
                    });

                }, 600);

            }
        }


        /* SAVE */
        let highestSlide = <?php echo $resumeSlide; ?>; // highest from DB

        function saveProgress() {

            // ONLY save if current slide is greater than highest saved
            if (currentSlide > highestSlide) {

                highestSlide = currentSlide; // update memory

                fetch("backend/save_progress.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `lesson=<?php echo $lesson; ?>&slide=${currentSlide}`
                });
            }
        }


        /* FULLSCREEN */
        function goFull() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }

        /* QUIZ */
        function submitQuiz(score) {

            fetch("backend/save_quiz.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `lesson=<?php echo $lesson; ?>&score=${score}`
            })
                .then(r => r.text())
                .then(res => {

                    document.getElementById("finalScore").innerHTML += `
<br><br>
<span style="color:#22c55e;font-weight:600">
✔ Lesson Completed & Score Saved!
</span>
`;

                    setTimeout(() => {
                        window.location.href = "lesson.php?quarter=<?php echo $qua; ?>";
                    }, 1500);

                });

        }



    </script>


    <script>
        let totalSlides = <?php echo $totalSlides; ?>;
        let currentSlide = <?php echo $resumeSlide; ?>; // resume here
    </script>



</body>

</html>