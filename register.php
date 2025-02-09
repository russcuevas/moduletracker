<?php
require 'database/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $type = $_POST["type"];

    $stmt = $conn->prepare("INSERT INTO tbl_users (email, password, type) VALUES (:email, :password, :type)");
    $stmt->execute([
        ":email" => $email,
        ":password" => $password,
        ":type" => $type
    ]);

    $user_id = $conn->lastInsertId();
    session_start();
    $_SESSION["user_id"] = $user_id;

    header("Location: information.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gen T Deleon National High School</title>
</head>

<body>
    <h1>Sign Up</h1>
    <form action="" method="POST">
        <label>Email: </label><br>
        <input type="email" name="email" required><br>
        <label>Password: </label><br>
        <input type="password" name="password" required><br>
        <label>Role: </label><br>
        <select name="type" required>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select><br>
        <button type="submit">Sign up</button>
        <a href="index.php">Login here</a>
    </form>
</body>

</html>