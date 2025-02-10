<?php
session_start();
require 'database/connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$query = "UPDATE tbl_students_subjects SET module_received = 0 WHERE student_id = :user_id";
$stmt = $conn->prepare($query);

if ($stmt->execute([":user_id" => $user_id])) {
    $_SESSION["success"] = "All modules have been successfully reset! 🔄";
} else {
    $_SESSION["error"] = "Failed to reset modules. Please try again. ❌";
}

// Redirect back to profile page
header("Location: profile.php");
exit;
