<?php
include 'database/connection.php';

// SESSION
session_start();
if (isset($_SESSION['user_id'])) {
    header('location:index.php');
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $select_user = $conn->prepare("SELECT * FROM `tbl_users` WHERE email = ? AND password = ? ");
    $select_user->execute([$email, $password]);

    if ($select_user->rowCount() > 0) {
        $user_id = $select_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user_id['user_id'];
        header('location:information.php');
    } else {
        $_SESSION['error'] = 'Incorrect email or password';
        header('location:index.php');
        exit();
    }
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
    <h1>Sign In</h1>
    <form action="" method="POST">
        <label for="">Email: </label><br>
        <input type="email" name="email"><br>
        <label for="">Password: </label><br>
        <input type="password" name="password"><br>
        <select name="type" id="">
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select><br>
        <button type="submit" name="login">Login</button>
        <a href="register.php">Sign up here</a>
    </form>
</body>

</html>