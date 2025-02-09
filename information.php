<?php
require 'database/connection.php';
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Fetch academic strands from the database
$stmt = $conn->prepare("SELECT id, strand_name FROM tbl_academic_strands");
$stmt->execute();
$strands = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION["user_id"];
    $fullname = $_POST["fullname"];
    $semester = $_POST["semester"];
    $academic_strand_id = $_POST["academic_strand_id"];
    $grade_level = $_POST["grade_level"];
    $section = $_POST["section"];

    // Insert into tbl_information
    $stmt = $conn->prepare("INSERT INTO tbl_information (user_id, semester, academic_strand_id, grade_level, section) 
                            VALUES (:user_id, :semester, :academic_strand_id, :grade_level, :section)");
    $stmt->execute([
        ":user_id" => $user_id,
        ":semester" => $semester,
        ":academic_strand_id" => $academic_strand_id,
        ":grade_level" => $grade_level,
        ":section" => $section
    ]);

    // Redirect to dashboard
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gen T Deleon National High School</title>
</head>

<body>
    <form action="" method="POST">
        <h1>Please fill up the requirements</h1>

        <label>Fullname:</label>
        <input type="text" name="fullname" required><br>

        <label>Your sem:</label>
        <select name="semester" required>
            <option value="1st Semester">1st Semester</option>
            <option value="2nd Semester">2nd Semester</option>
        </select><br>

        <label>Your academic strand:</label>
        <select name="academic_strand_id" required>
            <?php foreach ($strands as $strand) : ?>
                <option value="<?= $strand['id']; ?>"><?= htmlspecialchars($strand['strand_name']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Your grade level:</label>
        <select name="grade_level" required>
            <option value="Grade 11">Grade 11</option>
            <option value="Grade 12">Grade 12</option>
        </select><br>

        <label>Your section:</label>
        <select name="section" required>
            <option value="Thompson">Thompson</option>
        </select><br>

        <button type="submit">Proceed</button>
    </form>
</body>

</html>