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
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin-top: 80px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            width: 100%;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body>

    <a href="../logout.php" class="btn btn-danger logout-btn">Logout</a>

    <div class="container">
        <div class="card p-4">
            <h3 class="text-left">Module Finder</h3>
            <hr>

            <form action="dashboard.php" method="GET">
                <div class="mb-3">
                    <label class="form-label">Select Grade Level:</label>
                    <select style="border: 2px solid black;" name="grade_level" class="form-select" required>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Section:</label>
                    <select style="border: 2px solid black;" name="section" class="form-select" required>
                        <option value="Thompson">Thompson</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Academic Strand:</label>
                    <select style="border: 2px solid black;" name="academic_strand_id" class="form-select" required>
                        <?php foreach ($strands as $strand) : ?>
                            <option value="<?= $strand['id']; ?>"><?= htmlspecialchars($strand['strand_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Find</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>