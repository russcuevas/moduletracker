<?php
require 'database/connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];
    $fullname = isset($_POST["fullname"]) ? $_POST["fullname"] : "";

    // If user is a student, set fullname to empty
    if ($type === "student") {
        $fullname = "";
    }

    // Check if the email already exists
    $check_email = $conn->prepare("SELECT * FROM tbl_users WHERE email = :email");
    $check_email->execute([":email" => $email]);

    if ($check_email->rowCount() > 0) {
        $_SESSION["error"] = "Email is already taken. Please use a different email.";
    } else {
        // Insert the new user
        $stmt = $conn->prepare("INSERT INTO tbl_users (email, password, type, fullname) VALUES (:email, :password, :type, :fullname)");
        $stmt->execute([
            ":email" => $email,
            ":password" => $password, // Plain text password (should be hashed)
            ":type" => $type,
            ":fullname" => $fullname
        ]);

        // Store session and redirect
        $user_id = $conn->lastInsertId();
        $_SESSION["user_id"] = $user_id;
        $_SESSION["user_type"] = $type;

        if ($type === "teacher") {
            header("Location: admin/search.php");
        } else {
            header("Location: information.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Gen T Deleon National High School</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .register-container {
            max-width: 400px;
            margin: 80px auto;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            width: 100%;
        }

        .toggle-password {
            cursor: pointer;
        }

        .btn-outline-primary {
            background-color: black;
        }
    </style>
</head>

<body>

    <div class="register-container">
        <div class="card p-4">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <img src="logo.png" alt="Logo" class="me-2" style="height: 50px;">
                <h3 class="mb-0">Sign Up</h3>
            </div>

            <!-- Error Message -->
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div id="fullname-container" class="mb-3">
                    <label class="form-label">Full Name:</label>
                    <input style="border: 2px solid black !important;" type="text" name="fullname" id="fullname" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input style="border: 2px solid black !important;" type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <div class="input-group">
                        <input style="border: 2px solid black !important;" type="password" name="password" class="form-control" id="password" required>
                        <button class="btn btn-outline-primary toggle-password" type="button">
                            👁
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Type:</label>
                    <select style="border: 2px solid black !important;" name="type" id="type" class="form-select" required onchange="toggleFullNameField()">
                        <option value="teacher" selected>Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Sign Up</button>
                <div class="mt-3">
                    <a href="index.php" style="text-decoration: none;">Already have an account? Login here</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleFullNameField() {
            let typeSelect = document.getElementById("type");
            let fullNameField = document.getElementById("fullname-container");
            fullNameField.style.display = (typeSelect.value === "student") ? "none" : "block";
        }

        window.onload = function() {
            toggleFullNameField();
        };

        document.querySelector(".toggle-password").addEventListener("click", function() {
            let passwordField = document.getElementById("password");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>