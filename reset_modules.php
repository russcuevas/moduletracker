<?php
session_start();
require 'database/connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET["semester"])) {
    $_SESSION["error"] = "Invalid request!";
    header("Location: profile.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$semester = $_GET["semester"];

// Fetch subject IDs that belong to the selected semester
$query = "
    SELECT id FROM tbl_subjects 
    WHERE id IN (
        SELECT subject_id FROM tbl_students_subjects WHERE student_id = :user_id
    ) AND semester = :semester
";
$stmt = $conn->prepare($query);
$stmt->execute([
    ":user_id" => $user_id,
    ":semester" => $semester
]);
$subjectIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

if ($subjectIds) {
    $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
    $updateQuery = "UPDATE tbl_students_subjects SET module_received = 0 WHERE student_id = ? AND subject_id IN ($placeholders)";
    $updateStmt = $conn->prepare($updateQuery);
    if ($updateStmt->execute(array_merge([$user_id], $subjectIds))) {
        $_SESSION["success"] = "Modules for $semester successfully reset! 🔄";
    } else {
        $_SESSION["error"] = "Failed to reset modules for $semester. ❌";
    }
} else {
    $_SESSION["error"] = "No subjects found for $semester. ❌";
}

header("Location: profile.php");
exit;
