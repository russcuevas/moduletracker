<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gen T Deleon National High School</title>
</head>

<body>
    <h1>Module Finder</h1>
    <form action="dashboard.php" method="GET">
        <label>Select semester: </label>
        <select name="semester" required>
            <option value="1st Semester">1st Semester</option>
            <option value="2nd Semester">2nd Semester</option>
        </select><br>

        <label>Select grade level:</label>
        <select name="grade_level" required>
            <option value="Grade 11">Grade 11</option>
            <option value="Grade 12">Grade 12</option>
        </select><br>

        <label>Select section:</label>
        <select name="section" required>
            <option value="Thompson">Thompson</option>
        </select><br>

        <label>Select academic strand:</label>
        <select name="academic_strand_id" required>
            <?php
            require '../database/connection.php';

            $stmt = $conn->prepare("SELECT id, strand_name FROM tbl_academic_strands");
            $stmt->execute();
            $strands = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($strands as $strand) {
                echo "<option value='{$strand['id']}'>{$strand['strand_name']}</option>";
            }
            ?>
        </select><br>

        <button type="submit">Find</button>
    </form>
</body>

</html>