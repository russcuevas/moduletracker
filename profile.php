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

// Fetch 1st Semester subjects
$subjectQuery1 = "
    SELECT id, subject_name 
    FROM tbl_subjects 
    WHERE strand_id = :strand_id AND grade_level = :grade_level AND semester = '1st Semester'
";
$subjectStmt1 = $conn->prepare($subjectQuery1);
$subjectStmt1->execute([
    ":strand_id" => $strand_id,
    ":grade_level" => $profile["grade_level"]
]);
$subjects1 = $subjectStmt1->fetchAll(PDO::FETCH_ASSOC);

// Fetch 2nd Semester subjects
$subjectQuery2 = "
    SELECT id, subject_name 
    FROM tbl_subjects 
    WHERE strand_id = :strand_id AND grade_level = :grade_level AND semester = '2nd Semester'
";
$subjectStmt2 = $conn->prepare($subjectQuery2);
$subjectStmt2->execute([
    ":strand_id" => $strand_id,
    ":grade_level" => $profile["grade_level"]
]);
$subjects2 = $subjectStmt2->fetchAll(PDO::FETCH_ASSOC);

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        /* Custom Styling */
        body {
            background-color: #f8f9fa;
        }


        h4 {
            font-weight: 600;
        }

        .nav-tabs .nav-link {
            border-radius: 8px 8px 0 0;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            background-color: #1a1a1a;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>My Profile</h3>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <h4><?= htmlspecialchars($profile["fullname"]); ?></h4>
        <h5><?= htmlspecialchars($profile["grade_level"]); ?> - <?= htmlspecialchars($profile["section"]); ?></h5>
        <h5><?= htmlspecialchars($profile["strand_name"]); ?></h5>

        <?php if (isset($_SESSION["success"])) : ?>
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <?= $_SESSION["success"]; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-3" id="semesterTabs">
            <li class="nav-item">
                <a class="nav-link active" id="tab1" data-bs-toggle="tab" href="#firstSemester">1st Semester</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tab2" data-bs-toggle="tab" href="#secondSemester">2nd Semester</a>
            </li>
        </ul>

        <div class="tab-content">
            <!-- 1st Semester Table -->
            <div class="tab-pane fade show active" id="firstSemester">
                <h4 class="mb-3">1st Semester Subjects</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <?php foreach ($subjects1 as $subject) : ?>
                                <th><?= htmlspecialchars($subject["subject_name"]); ?></th>
                            <?php endforeach; ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?= htmlspecialchars($profile["fullname"]); ?></td>
                            <?php foreach ($subjects1 as $subject) : ?>
                                <td>
                                    <?php if (isset($modules[$subject["id"]]) && $modules[$subject["id"]]) : ?>
                                        ✔
                                    <?php else : ?>
                                        <a href="update_module.php?subject_id=<?= $subject["id"]; ?>" class="btn btn-primary btn-sm btn-custom">
                                            Mark as Checked <span style="font-size: 18px;">✔</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td><a href="reset_modules.php?semester=1st Semester" class="btn btn-danger btn-custom">Reset</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- 2nd Semester Table -->
            <div class="tab-pane fade" id="secondSemester">
                <h4 class="mb-3">2nd Semester Subjects</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <?php foreach ($subjects2 as $subject) : ?>
                                <th><?= htmlspecialchars($subject["subject_name"]); ?></th>
                            <?php endforeach; ?>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><?= htmlspecialchars($profile["fullname"]); ?></td>
                            <?php foreach ($subjects2 as $subject) : ?>
                                <td>
                                    <?php if (isset($modules[$subject["id"]]) && $modules[$subject["id"]]) : ?>
                                        ✔
                                    <?php else : ?>
                                        <a href="update_module.php?subject_id=<?= $subject["id"]; ?>" class="btn btn-primary btn-sm btn-custom">
                                            Mark as Checked <span style="font-size: 18px;">✔</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                            <td><a href="reset_modules.php?semester=2nd Semester" class="btn btn-danger btn-custom">Reset</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>