<?php
session_start();
require 'database/connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$query = "
    SELECT u.email, i.fullname, i.grade_level, i.section, s.strand_name, i.academic_strand_id
    FROM tbl_users u
    LEFT JOIN tbl_information i ON u.id = i.user_id
    LEFT JOIN tbl_academic_strands s ON i.academic_strand_id = s.id
    WHERE u.id = :user_id
";
$stmt = $conn->prepare($query);
$stmt->execute([":user_id" => $user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$profile) {
    die("Student profile not found.");
}

$strand_id = $profile["academic_strand_id"];

$subjectQuery = "
    SELECT id, subject_name 
    FROM tbl_subjects 
    WHERE strand_id = :strand_id AND grade_level = :grade_level
";
$subjectStmt = $conn->prepare($subjectQuery);
$subjectStmt->execute([
    ":strand_id" => $strand_id,
    ":grade_level" => $profile["grade_level"]
]);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);


$moduleQuery = "
    SELECT subject_id, module_received 
    FROM tbl_students_subjects 
    WHERE student_id = :user_id
";
$moduleStmt = $conn->prepare($moduleQuery);
$moduleStmt->execute([":user_id" => $user_id]);
$modules = $moduleStmt->fetchAll(PDO::FETCH_KEY_PAIR);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gen T Deleon National High School</title>
    <link href="bootstrap-profile.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between">
            <h3>My Profile</h3>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <h4><?= htmlspecialchars($profile["fullname"]); ?></h4>
        <h4><?= htmlspecialchars($profile["grade_level"]); ?> - <?= htmlspecialchars($profile["section"]); ?></h4>
        <h4><?= htmlspecialchars($profile["strand_name"]); ?></h4>
        <?php
        if (isset($_SESSION["success"])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION["success"]; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION["success"]);
            ?>
        <?php endif; ?>
        <table class="table table-bordered" style="border: 3px solid black !important;">
            <thead>
                <tr>
                    <th style="border: 3px solid black !important;">#</th>
                    <th style="border: 3px solid black !important;">Student Name</th>
                    <?php foreach ($subjects as $subject) : ?>
                        <th style="border: 3px solid black !important;"><?= htmlspecialchars($subject["subject_name"]); ?></th>
                    <?php endforeach; ?>
                    <th style="border: 3px solid black !important;">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border: 3px solid black !important;">1</td>
                    <td style="border: 3px solid black !important;"><?= htmlspecialchars($profile["fullname"]); ?></td>
                    <?php foreach ($subjects as $subject) : ?>
                        <td style="border: 3px solid black !important;">
                            <?php if (isset($modules[$subject["id"]]) && $modules[$subject["id"]]) : ?>
                                ✔
                            <?php else : ?>
                                <a href="update_module.php?subject_id=<?= $subject["id"]; ?>" class="btn btn-success btn-sm">
                                    Mark as Checked <span style="font-size: 20px;">✔</span>
                                </a>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                    <td style="border: 3px solid black !important;"><a href="reset_modules.php" class="btn btn-danger">Reset</a></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>