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
    <title>Login | Gen T Deleon National High School</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
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

    <div class="login-container">
        <div class="card p-4">
            <div class="d-flex align-items-center justify-content-center mb-3">
                <img src="logo.png" alt="Logo" class="me-2" style="height: 50px;">
                <h3 class="mb-0">Sign In</h3>
            </div>

            <!-- Error Message -->
            <?php if (isset($_SESSION['error'])) : ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
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
                    <label class="form-label">Login as:</label>
                    <select style="border: 2px solid black !important;" name="type" class="form-select" required>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <button type="submit" name="login" class="btn btn-primary">Login</button>
                <div class="mt-3">
                    <a href="register.php" style="text-decoration: none;">Click to sign up</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.querySelector(".toggle-password").addEventListener("click", function() {
            let passwordField = document.getElementById("password");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>