<?php
include 'database/connection.php';

// SESSION
session_start();
if (isset($_SESSION['user_id'])) {
    header('location:dashboard.php');
    exit();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    $select_user = $conn->prepare("SELECT * FROM `tbl_users` WHERE email = ? AND password = ? AND type = ?");
    $select_user->execute([$email, $password, $type]);

    if ($select_user->rowCount() > 0) {
        $user = $select_user->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['type'];

        if ($user['type'] == 'teacher') {
            header('location: admin/search.php');
        } else {
            header('location: profile.php');
        }
        exit();
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

    <?php if (isset($_SESSION['error'])): ?>
        <p style="color: red;"><?= $_SESSION['error'];
                                unset($_SESSION['error']); ?>
        </p>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Email: </label><br>
        <input type="email" name="email" required><br>

        <label>Password: </label><br>
        <input type="password" name="password" required><br>

        <label>Login as:</label><br>
        <select name="type" required>
            <option value="teacher">Teacher</option>
            <option value="student">Student</option>
        </select><br>

        <button type="submit" name="login">Login</button>
        <a href="register.php">Sign up here</a>
    </form>
</body>

</html>