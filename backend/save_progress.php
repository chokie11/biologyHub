<?php
session_start();
include "db.php";

if (!isset($_SESSION["usrid"])) {
    exit();
}

$student_id = $_SESSION["usrid"];
$lesson = $_POST['lesson'];
$newSlide = (int) $_POST['slide'];

/* Check existing progress */
$q = $conn->query("SELECT slide_number FROM lesson_progress 
                   WHERE student_id='$student_id' 
                   AND lesson_name='$lesson'");

if ($q && $q->num_rows > 0) {

    $row = $q->fetch_assoc();
    $currentSaved = (int) $row['slide_number'];

    // ONLY update if new slide is higher
    if ($newSlide > $currentSaved) {

        $conn->query("UPDATE lesson_progress 
                      SET slide_number='$newSlide' 
                      WHERE student_id='$student_id' 
                      AND lesson_name='$lesson'");
    }

} else {

    // First time saving
    $conn->query("INSERT INTO lesson_progress 
                  (student_id, lesson_name, slide_number) 
                  VALUES ('$student_id', '$lesson', '$newSlide')");
}
