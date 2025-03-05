<?php
session_start();

// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hardcoded admin credentials
    $admin_email = "admin@gmail.com";
    $admin_password = "12345";

    if ($email === $admin_email && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Hotel Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Admin Login</h2>
        <div class="col-md-4 offset-md-4">
            <form method="POST" action="">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
