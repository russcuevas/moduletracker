<?php
session_start();
require 'database/connection.php';

if (!isset($_SESSION["user_id"]) || !isset($_GET["subject_id"])) {
    header("Location: profile.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$subject_id = $_GET["subject_id"];

// Check if the record already exists
$query = "SELECT COUNT(*) FROM tbl_students_subjects WHERE student_id = :user_id AND subject_id = :subject_id";
$stmt = $conn->prepare($query);
$stmt->execute([":user_id" => $user_id, ":subject_id" => $subject_id]);
$exists = $stmt->fetchColumn();

if ($exists) {
    // Update existing record
    $updateQuery = "UPDATE tbl_students_subjects SET module_received = 1 WHERE student_id = :user_id AND subject_id = :subject_id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->execute([":user_id" => $user_id, ":subject_id" => $subject_id]);
} else {
    // Insert new record
    $insertQuery = "INSERT INTO tbl_students_subjects (student_id, subject_id, module_received) VALUES (:user_id, :subject_id, 1)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->execute([":user_id" => $user_id, ":subject_id" => $subject_id]);
}

// Redirect back to profile page
header("Location: profile.php");
exit;
