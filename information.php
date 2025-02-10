<?php
require 'database/connection.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "student") {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT id, strand_name FROM tbl_academic_strands");
$stmt->execute();
$strands = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $academic_strand_id = $_POST["academic_strand_id"];
    $grade_level = $_POST["grade_level"];
    $section = $_POST["section"];

    $stmt = $conn->prepare("INSERT INTO tbl_information (user_id, fullname, academic_strand_id, grade_level, section) 
                            VALUES (:user_id, :fullname, :academic_strand_id, :grade_level, :section)");
    $stmt->execute([
        ":user_id" => $user_id,
        ":fullname" => $fullname,
        ":academic_strand_id" => $academic_strand_id,
        ":grade_level" => $grade_level,
        ":section" => $section
    ]);

    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
</head>

<body>
    <form action="" method="POST">
        <h1>Please fill up the requirements</h1>

        <label>Full Name:</label>
        <input type="text" name="fullname" required><br>

        <label>Academic Strand:</label>
        <select name="academic_strand_id" required>
            <?php foreach ($strands as $strand) : ?>
                <option value="<?= $strand['id']; ?>"><?= htmlspecialchars($strand['strand_name']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <label>Grade Level:</label>
        <select name="grade_level" required>
            <option value="Grade 11">Grade 11</option>
            <option value="Grade 12">Grade 12</option>
        </select><br>

        <label>Section:</label>
        <select name="section" required>
            <option value="Thompson">Thompson</option>
        </select><br>

        <button type="submit">Proceed</button>
    </form>
</body>

</html>