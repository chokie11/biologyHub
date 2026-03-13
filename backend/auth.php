<?php
session_start();

if (!isset($_SESSION["usrid"])) {
    // user not logged in
    header("Location: login.php");
    exit();
}
