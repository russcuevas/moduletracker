<?php
require '../database/connection.php';
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_type"] !== "teacher")) {
    header("Location: ../index.php");
    exit;
}

$teacher_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT fullname FROM tbl_users WHERE id = :teacher_id");
$stmt->execute([':teacher_id' => $teacher_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);
$teacher_name = $teacher ? $teacher['fullname'] : 'Teacher';

$grade_level = $_GET['grade_level'] ?? '';
$section = $_GET['section'] ?? '';
$academic_strand_id = $_GET['academic_strand_id'] ?? '';

$strand_name = 'Unknown Strand';
if (!empty($academic_strand_id)) {
    $strandStmt = $conn->prepare("SELECT strand_name FROM tbl_academic_strands WHERE id = :academic_strand_id");
    $strandStmt->execute([':academic_strand_id' => $academic_strand_id]);
    $strand = $strandStmt->fetch(PDO::FETCH_ASSOC);
    $strand_name = $strand['strand_name'] ?? 'Unknown Strand';
}

$stmt = $conn->prepare("SELECT u.id, u.email, i.fullname, i.grade_level, i.section, i.academic_strand_id 
                        FROM tbl_information i
                        JOIN tbl_users u ON i.user_id = u.id
                        WHERE i.grade_level = :grade_level 
                          AND i.section = :section 
                          AND i.academic_strand_id = :academic_strand_id");
$stmt->execute([
    ':grade_level' => $grade_level,
    ':section' => $section,
    ':academic_strand_id' => $academic_strand_id
]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subjects = [];
if (!empty($academic_strand_id)) {
    $subjectStmt = $conn->prepare("SELECT id, subject_name FROM tbl_subjects WHERE strand_id = :strand_id");
    $subjectStmt->execute([':strand_id' => $academic_strand_id]);
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Welcome! <?php echo htmlspecialchars($teacher_name); ?></h3>
            <div class="d-flex gap-2">
                <a href="search.php" class="btn btn-primary">Find Module</a>
                <a href="../logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>

        <h3><?= htmlspecialchars($grade_level) ?> - <?= htmlspecialchars($section) ?></h3>

        <div class="d-flex align-items-center">
            <h4><?= htmlspecialchars($strand_name) ?> -&nbsp;</h4>
            <a href="edit_subject.php?strand_id=<?= htmlspecialchars($academic_strand_id) ?>&back_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>"
                class="btn btn-success" style="text-decoration: none; margin-top: -5px">CLICK HERE TO MODIFY SUBJECTS</a>
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
                        <td colspan="<?= count($subjects) + 3 ?>">No students found.</td>
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