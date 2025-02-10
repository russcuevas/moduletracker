<?php
require '../database/connection.php';
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_type"] !== "teacher")) {
    header("Location: ../index.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, strand_name FROM tbl_academic_strands");
$stmt->execute();
$strands = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Finder - Gen T Deleon National High School</title>
</head>

<body>
    <h1>Module Finder</h1>
    <a href="../logout.php">Logout</a>

    <form action="dashboard.php" method="GET">
        <label>Select Grade Level:</label>
        <select name="grade_level" required>
            <option value="Grade 11">Grade 11</option>
            <option value="Grade 12">Grade 12</option>
        </select><br>

        <label>Select Section:</label>
        <select name="section" required>
            <option value="Thompson">Thompson</option>
        </select><br>

        <label>Select Academic Strand:</label>
        <select name="academic_strand_id" required>
            <?php foreach ($strands as $strand) : ?>
                <option value="<?= $strand['id']; ?>"><?= htmlspecialchars($strand['strand_name']); ?></option>
            <?php endforeach; ?>
        </select><br>

        <button type="submit">Find</button>
    </form>
</body>

</html>