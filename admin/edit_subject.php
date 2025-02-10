<?php
require '../database/connection.php';
session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["user_type"] !== "teacher")) {
    header("Location: ../index.php");
    exit;
}

$strand_id = $_GET['strand_id'] ?? '';
$grade_level = $_GET['grade_level'] ?? '';
$semester = $_GET['semester'] ?? '';
$back_url = $_GET['back_url'] ?? 'dashboard.php';

$strandStmt = $conn->prepare("SELECT strand_name FROM tbl_academic_strands WHERE id = :strand_id");
$strandStmt->execute([':strand_id' => $strand_id]);
$strand = $strandStmt->fetch(PDO::FETCH_ASSOC);

if (!$strand) {
    die("Invalid strand ID.");
}

$subjectStmt = $conn->prepare("SELECT id, subject_name FROM tbl_subjects WHERE strand_id = :strand_id AND grade_level = :grade_level AND semester = :semester ORDER BY subject_name");
$subjectStmt->execute([':strand_id' => $strand_id, ':grade_level' => $grade_level, ':semester' => $semester]);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_subject'])) {
        $subject_name = trim($_POST['subject_name']);

        if (!empty($subject_name)) {
            $insertStmt = $conn->prepare("INSERT INTO tbl_subjects (strand_id, subject_name, grade_level, semester) VALUES (:strand_id, :subject_name, :grade_level, :semester)");
            $insertStmt->execute([
                ':strand_id' => $strand_id,
                ':subject_name' => $subject_name,
                ':grade_level' => $grade_level,
                ':semester' => $semester
            ]);
            $_SESSION['success'] = "Subject added successfully!";
            header("Location: edit_subject.php?strand_id=$strand_id&grade_level=$grade_level&semester=$semester&back_url=" . urlencode($back_url));
            exit();
        }
    }


    if (isset($_POST['delete_subject'])) {
        $subject_id = $_POST['subject_id'];
        $deleteStmt = $conn->prepare("DELETE FROM tbl_subjects WHERE id = :subject_id");
        $deleteStmt->execute([':subject_id' => $subject_id]);
        $_SESSION['success'] = "Subject deleted successfully!";
        header("Location: edit_subject.php?strand_id=$strand_id&grade_level=$grade_level&semester=$semester&back_url=" . urlencode($back_url));
        exit();
    }

    if (isset($_POST['update_subject'])) {
        $subject_id = $_POST['subject_id'];
        $updated_subject_name = trim($_POST['updated_subject_name']);

        if (!empty($updated_subject_name)) {
            $updateStmt = $conn->prepare("UPDATE tbl_subjects SET subject_name = :subject_name WHERE id = :subject_id");
            $updateStmt->execute([':subject_name' => $updated_subject_name, ':subject_id' => $subject_id]);
            $_SESSION['success'] = "Subject updated successfully!";
            header("Location: edit_subject.php?strand_id=$strand_id&grade_level=$grade_level&semester=$semester&back_url=" . urlencode($back_url));
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subjects</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        .editing {
            background-color: yellow;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h3>Edit Subjects for <?php echo $semester ?>&nbsp;<?= htmlspecialchars($strand['strand_name']) ?> - <?= htmlspecialchars($grade_level) ?></h3>
        <a href="<?= htmlspecialchars($back_url) ?>" class="btn btn-primary mb-3">Back to Dashboard</a>

        <?php if (isset($_SESSION['success'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <table class="table table-dark">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($subjects)) :
                    $counter = 1;
                    foreach ($subjects as $subject) : ?>
                        <tr>
                            <td><?= $counter++ ?></td>
                            <td>
                                <input type="text" id="subject-<?= $subject['id'] ?>" value="<?= htmlspecialchars($subject['subject_name']) ?>" class="form-control" disabled>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="enableEditing(<?= $subject['id'] ?>)">Edit</button>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
                                    <button type="submit" name="delete_subject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="3">No subjects found for <?= htmlspecialchars($grade_level) ?>.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form method="POST" id="update-form" class="mb-3" style="display: none;">
            <input type="hidden" id="update-subject-id" name="subject_id">
            <div class="col-md-6 mb-2">
                <label for="updated_subject_name" class="form-label">Modify Subject Name:</label>
                <input style="border: 2px solid black !important;" type="text" id="updated_subject_name" name="updated_subject_name" class="form-control" required>
            </div>
            <button type="submit" name="update_subject" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-warning" onclick="cancelEditing()">Cancel</button>
        </form>

        <h4>Add New Subject</h4>
        <form method="POST" class="mb-3">
            <div class="col-md-6 mb-2">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input style="border: 2px solid black !important;" type="text" id="subject_name" name="subject_name" class="form-control" required>
            </div>
            <button type="submit" name="add_subject" class="btn btn-primary">Add Subject</button>
        </form>
    </div>

    <script>
        let currentEditingId = null;

        function enableEditing(subjectId) {
            if (currentEditingId) {
                cancelEditing();
            }

            currentEditingId = subjectId;

            let inputField = document.getElementById('subject-' + subjectId);
            inputField.disabled = false;
            inputField.classList.add('editing');

            document.getElementById('updated_subject_name').value = inputField.value;
            document.getElementById('update-subject-id').value = subjectId;
            document.getElementById('update-form').style.display = 'block';
        }

        function cancelEditing() {
            if (currentEditingId) {
                let inputField = document.getElementById('subject-' + currentEditingId);
                inputField.disabled = true;
                inputField.classList.remove('editing');
            }
            document.getElementById('update-form').style.display = 'none';
            currentEditingId = null;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>