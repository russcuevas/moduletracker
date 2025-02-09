<?php
require '../database/connection.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'teacher') {
    header('location: index.php');
    exit();
}

// Get the strand_id from URL
$strand_id = $_GET['strand_id'] ?? '';
$back_url = $_GET['back_url'] ?? 'dashboard.php';

// Fetch strand details
$strandStmt = $conn->prepare("SELECT strand_name FROM tbl_academic_strands WHERE id = :strand_id");
$strandStmt->execute([':strand_id' => $strand_id]);
$strand = $strandStmt->fetch(PDO::FETCH_ASSOC);

if (!$strand) {
    die("Invalid strand ID.");
}

// Fetch subjects for this strand
$subjectStmt = $conn->prepare("SELECT id, subject_name FROM tbl_subjects WHERE strand_id = :strand_id");
$subjectStmt->execute([':strand_id' => $strand_id]);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle adding a subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_subject'])) {
    $subject_name = trim($_POST['subject_name']);

    if (!empty($subject_name)) {
        $insertStmt = $conn->prepare("INSERT INTO tbl_subjects (strand_id, subject_name) VALUES (:strand_id, :subject_name)");
        $insertStmt->execute([':strand_id' => $strand_id, ':subject_name' => $subject_name]);
        header("Location: edit_subject.php?strand_id=$strand_id&back_url=" . urlencode($back_url));
        exit();
    }
}

// Handle deleting a subject
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_subject'])) {
    $subject_id = $_POST['subject_id'];
    $deleteStmt = $conn->prepare("DELETE FROM tbl_subjects WHERE id = :subject_id");
    $deleteStmt->execute([':subject_id' => $subject_id]);
    header("Location: edit_subject.php?strand_id=$strand_id&back_url=" . urlencode($back_url));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h3>Edit Subjects for <?= htmlspecialchars($strand['strand_name']) ?> (ID: <?= htmlspecialchars($strand_id) ?>)</h3>
        <a href="<?= htmlspecialchars($back_url) ?>" class="btn btn-secondary mb-3">Back to Dashboard</a>

        <table class="table table-bordered">
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
                            <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                            <td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>