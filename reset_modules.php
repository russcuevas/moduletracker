<?php
session_start();
require 'database/connection.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header('location: index.php');
    exit();
}

$user_id = $_SESSION["user_id"];

$query = "UPDATE tbl_students_subjects SET module_received = 0 WHERE student_id = :user_id";
$stmt = $conn->prepare($query);

if ($stmt->execute([":user_id" => $user_id])) {
    header("Location: profile.php?reset=success");
    exit;
} else {
    header("Location: profile.php?reset=error");
    exit;
}
