<?php
require '../database/connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'teacher') {
    header('location: index.php');
    exit();
}

$strand_id = $_GET['strand_id'] ?? '';
$back_url = $_GET['back_url'] ?? 'dashboard.php';

$strandStmt = $conn->prepare("SELECT strand_name FROM tbl_academic_strands WHERE id = :strand_id");
$strandStmt->execute([':strand_id' => $strand_id]);
$strand = $strandStmt->fetch(PDO::FETCH_ASSOC);

if (!$strand) {
    die("Invalid strand ID.");
}

$subjectStmt = $conn->prepare("SELECT id, subject_name FROM tbl_subjects WHERE strand_id = :strand_id");
$subjectStmt->execute([':strand_id' => $strand_id]);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle Adding Subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subject'])) {
    $subject_name = trim($_POST['subject_name']);

    if (!empty($subject_name)) {
        $insertStmt = $conn->prepare("INSERT INTO tbl_subjects (strand_id, subject_name) VALUES (:strand_id, :subject_name)");
        $insertStmt->execute([':strand_id' => $strand_id, ':subject_name' => $subject_name]);
        header("Location: edit_subject.php?strand_id=$strand_id&back_url=" . urlencode($back_url));
        exit();
    }
}

// Handle Deleting Subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_subject'])) {
    $subject_id = $_POST['subject_id'];
    $deleteStmt = $conn->prepare("DELETE FROM tbl_subjects WHERE id = :subject_id");
    $deleteStmt->execute([':subject_id' => $subject_id]);
    header("Location: edit_subject.php?strand_id=$strand_id&back_url=" . urlencode($back_url));
    exit();
}

// Handle Updating Subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_subject'])) {
    $subject_id = $_POST['subject_id'];
    $updated_subject_name = trim($_POST['updated_subject_name']);

    if (!empty($updated_subject_name)) {
        $updateStmt = $conn->prepare("UPDATE tbl_subjects SET subject_name = :subject_name WHERE id = :subject_id");
        $updateStmt->execute([':subject_name' => $updated_subject_name, ':subject_id' => $subject_id]);
        header("Location: edit_subject.php?strand_id=$strand_id&back_url=" . urlencode($back_url));
        exit();
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
        <h3>Edit Subjects for <?= htmlspecialchars($strand['strand_name']) ?></h3>
        <a href="<?= htmlspecialchars($back_url) ?>" class="btn btn-primary mb-3">Back to Dashboard</a>

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
                        <tr id="row-<?= $subject['id'] ?>">
                            <td><?= $counter++ ?></td>
                            <td>
                                <input type="text" id="subject-<?= $subject['id'] ?>" value="<?= htmlspecialchars($subject['subject_name']) ?>" class="form-control" disabled>
                            </td>
                            <td>
                                <!-- Update Button -->
                                <button class="btn btn-warning btn-sm" onclick="enableEditing(<?= $subject['id'] ?>)">Update</button>
                                <!-- Delete Form -->
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="subject_id" value="<?= $subject['id'] ?>">
                                    <button type="submit" name="delete_subject" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subject?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td colspan="3">No subjects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Update Subject Form -->
        <form method="POST" id="update-form" class="mb-3" style="display: none;">
            <input type="hidden" id="update-subject-id" name="subject_id">
            <div class="mb-2">
                <label for="updated_subject_name" class="form-label">Modify Subject Name:</label>
                <input type="text" id="updated_subject_name" name="updated_subject_name" class="form-control" required>
            </div>
            <button type="submit" name="update_subject" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-warning" onclick="cancelEditing()">Cancel</button>
        </form>

        <!-- Add Subject Form -->
        <h4>Add New Subject</h4>
        <form method="POST" class="mb-3">
            <div class="mb-2">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" id="subject_name" name="subject_name" class="form-control" required>
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