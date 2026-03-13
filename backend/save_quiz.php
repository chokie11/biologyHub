<?php
session_start();
include "db.php";

if (!isset($_SESSION["usrid"])) {
    echo "Not logged in";
    exit();
}

$student_id = $_SESSION["usrid"];
$lesson = $_POST['lesson'] ?? '';
$score = $_POST['score'] ?? 0;

/* mark completed if passed */
$completed = ($score >= 1) ? 1 : 0; // change logic if needed

/* check if record exists */
$check = $conn->query("SELECT id FROM lesson_progress 
WHERE student_id='$student_id' AND lesson_name='$lesson'");

if ($check->num_rows > 0) {
    // update
    $conn->query("UPDATE lesson_progress 
        SET quiz_score='$score', completed='$completed'
        WHERE student_id='$student_id' AND lesson_name='$lesson'");
} else {
    // insert
    $conn->query("INSERT INTO lesson_progress 
    (student_id, lesson_name, quiz_score, completed) 
    VALUES ('$student_id','$lesson','$score','$completed')");
}

echo "saved";
?>
