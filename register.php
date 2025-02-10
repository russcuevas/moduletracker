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

    $stmt = $conn->prepare("INSERT INTO tbl_users (email, password, type, fullname) VALUES (:email, :password, :type, :fullname)");
    $stmt->execute([
        ":email" => $email,
        ":password" => $password, // Plain text password
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Gen T Deleon National High School</title>
    <script>
        function toggleFullNameField() {
            var typeSelect = document.getElementById("type");
            var fullNameField = document.getElementById("fullname-container");
            fullNameField.style.display = (typeSelect.value === "student") ? "none" : "block";
        }

        window.onload = function() {
            toggleFullNameField();
        };
    </script>
</head>

<body>
    <h1>Sign Up</h1>
    <form action="" method="POST">
        <div id="fullname-container">
            <label>Full Name:</label><br>
            <input type="text" name="fullname" id="fullname"><br>
        </div>

        <label>Email:</label><br>
        <input type="email" name="email" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br>

        <label>Type:</label><br>
        <select name="type" id="type" onchange="toggleFullNameField()" required>
            <option value="teacher" selected>Teacher</option>
            <option value="student">Student</option>
        </select><br>

        <button type="submit">Sign Up</button>
        <a href="index.php">Login here</a>
    </form>
</body>

</html>