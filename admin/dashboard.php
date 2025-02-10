<?php
require '../database/connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'teacher') {
    header('location: index.php');
    exit();
}

// Retrieve filter values from GET request
$semester = $_GET['semester'] ?? '';
$grade_level = $_GET['grade_level'] ?? '';
$section = $_GET['section'] ?? '';
$academic_strand_id = $_GET['academic_strand_id'] ?? '';

// Fetch students based on filters
$stmt = $conn->prepare("SELECT u.id, u.email, i.fullname, i.grade_level, i.section, a.strand_name, i.academic_strand_id 
                        FROM tbl_information i
                        JOIN tbl_users u ON i.user_id = u.id
                        JOIN tbl_academic_strands a ON i.academic_strand_id = a.id
                        WHERE i.semester = :semester 
                          AND i.grade_level = :grade_level 
                          AND i.section = :section 
                          AND i.academic_strand_id = :academic_strand_id");
$stmt->execute([
    ':semester' => $semester,
    ':grade_level' => $grade_level,
    ':section' => $section,
    ':academic_strand_id' => $academic_strand_id
]);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subjects = [];
$strand_id = $academic_strand_id;
$strand_name = '';

// Fetch strand details
if (!empty($students)) {
    $strand_id = $students[0]['academic_strand_id'];
    $strand_name = $students[0]['strand_name'];

    $subjectStmt = $conn->prepare("SELECT id, subject_name FROM tbl_subjects WHERE strand_id = :strand_id");
    $subjectStmt->execute([':strand_id' => $strand_id]);
    $subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="bootstrap.min.css">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Teacher</h3>
            <div class="d-flex gap-2">
                <a href="search.php" class="btn btn-primary">Find Module</a>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <h3><?= htmlspecialchars($semester) ?></h3>
        <h3><?= htmlspecialchars($grade_level) ?> - <?= htmlspecialchars($section) ?></h3>

        <div class="d-flex align-items-center">
            <h4 class=""><?= htmlspecialchars($strand_name) ?> -&nbsp;</h4>
            <a href="edit_subject.php?strand_id=<?= $strand_id ?>&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>" style="text-decoration: none; margin-top: -5px" class="btn btn-success">CLICK HERE TO MODIFY SUBJECTS</a>
        </div>

        <table id="studentsTable" class="table table-dark mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <?php if (!empty($subjects)) : ?>
                        <?php foreach ($subjects as $subject) : ?>
                            <th><?= htmlspecialchars($subject['subject_name']) ?></th>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <th colspan="1">No subjects available</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($students)) {
                    $counter = 1;
                    foreach ($students as $student) {
                        $moduleStmt = $conn->prepare("SELECT subject_id, module_received FROM tbl_students_subjects WHERE student_id = :student_id");
                        $moduleStmt->execute([':student_id' => $student['id']]);
                        $modules = $moduleStmt->fetchAll(PDO::FETCH_KEY_PAIR);
                ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td><?= htmlspecialchars($student['fullname']) ?></td>
                            <?php if (!empty($subjects)) : ?>
                                <?php foreach ($subjects as $subject) : ?>
                                    <td>
                                        <?= isset($modules[$subject['id']]) && $modules[$subject['id']] ? '✔' : '❌' ?>
                                    </td>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <td>No subjects</td>
                            <?php endif; ?>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="<?= count($subjects) + 2 ?>">No students found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                "lengthChange": false,
            });
        });
    </script>
</body>

</html>