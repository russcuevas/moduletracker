<?php
require 'database/connection.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] !== "student") {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION["user_id"];

$stmt = $conn->prepare("SELECT id, strand_name FROM tbl_academic_strands");
$stmt->execute();
$strands = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $academic_strand_id = $_POST["academic_strand_id"];
    $grade_level = $_POST["grade_level"];
    $section = $_POST["section"];

    $stmt = $conn->prepare("INSERT INTO tbl_information (user_id, fullname, academic_strand_id, grade_level, section) 
                            VALUES (:user_id, :fullname, :academic_strand_id, :grade_level, :section)");
    $stmt->execute([
        ":user_id" => $user_id,
        ":fullname" => $fullname,
        ":academic_strand_id" => $academic_strand_id,
        ":grade_level" => $grade_level,
        ":section" => $section
    ]);

    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 500px;
            margin-top: 50px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            width: 100%;
            background-color: black;
            border: none;
        }

        .btn-primary:hover {
            background-color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card p-4">
            <h3 class="text-left mb-4">Fill Up the Requirements</h3>
            <form action="" method="POST">

                <div class="mb-3">
                    <label class="form-label">Full Name:</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Grade Level:</label>
                    <select name="grade_level" id="grade_level" class="form-select" required>
                        <option value="">Select Grade Level</option>
                        <option value="Grade 11">Grade 11</option>
                        <option value="Grade 12">Grade 12</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Academic Strand:</label>
                    <select name="academic_strand_id" id="academic_strand" class="form-select" required>
                        <option value="">Select Strand</option>
                        <?php foreach ($strands as $strand) : ?>
                            <option value="<?= $strand['id']; ?>"><?= htmlspecialchars($strand['strand_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Section:</label>
                    <select name="section" id="section" class="form-select" required>
                        <option value="">Select Section</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Proceed</button>
            </form>
        </div>
    </div>

    <script>
        const sections = {
            "Grade 11": {
                "HE": ["Ramsay"],
                "HUMSS": ["Weber", "Marx"],
                "ICT": ["Jobs"],
                "STEM": ["Euclid", "Curie"],
                "GAS": ["Darwin"],
                "ABM": ["Pacioli"],
                "Tourism": ["Heritage"]
            },
            "Grade 12": {
                "ICT": ["Thompson", "Liskov"],
                "ABM": ["Ayala"],
                "HUMSS": ["Aristotle", "Durkheim"],
                "HE": ["Ducasse", "Lawson"],
                "STEM": ["Einstein"],
                "GAS": ["Descartes"]
            }
        };

        document.getElementById("grade_level").addEventListener("change", updateSections);
        document.getElementById("academic_strand").addEventListener("change", updateSections);

        function updateSections() {
            const gradeLevel = document.getElementById("grade_level").value;
            const strand = document.getElementById("academic_strand").selectedOptions[0]?.text;

            const sectionSelect = document.getElementById("section");
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (sections[gradeLevel] && sections[gradeLevel][strand]) {
                sections[gradeLevel][strand].forEach(section => {
                    const option = document.createElement("option");
                    option.value = section;
                    option.textContent = section;
                    sectionSelect.appendChild(option);
                });
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>