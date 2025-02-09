<?php
session_start();
require 'database/connection.php';

if (!isset($_SESSION["user_id"]) || !isset($_GET["subject_id"])) {
    header("Location: profile.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$subject_id = $_GET["subject_id"];

$query = "UPDATE tbl_students_subjects SET module_received = 1 WHERE student_id = :user_id AND subject_id = :subject_id";
$stmt = $conn->prepare($query);
$stmt->execute([":user_id" => $user_id, ":subject_id" => $subject_id]);

header("Location: profile.php");
exit;
