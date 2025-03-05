<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE user_id = '$user_id' AND password = '$password'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['user_id'] = $user_id;
        header("Location: index.php");
        exit();
    } else {
        echo "<script>alert('Invalid Credentials!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Café Crepe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* Full-Screen Centering */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #ff758c, #ff7eb3, #fad0c4);
}

/* Login Container */
.login-container {
    background: rgba(255, 255, 255, 0.95); /* Soft Glass Effect */
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.25);
    text-align: center;
    max-width: 420px;
    width: 100%;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    animation: fadeIn 0.8s ease-in-out;
}

/* Login Title */
.login-container h2 {
    margin-bottom: 10px;
    font-size: 26px;
    font-weight: bold;
    color: #ff416c;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 2px 2px 5px rgba(255, 65, 108, 0.3);
}

/* Input Fields */
.login-container input {
    width: 100%;
    padding: 14px;
    margin: 12px 0;
    border: 2px solid rgba(255, 65, 108, 0.3);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
    transition: 0.3s ease-in-out;
    font-size: 16px;
    color: #333;
}

/* Input Focus Effect */
.login-container input:focus {
    border-color: #ff416c;
    outline: none;
    box-shadow: 0px 0px 12px rgba(255, 65, 108, 0.5);
    background: white;
}

/* Submit Button */
.login-container button {
    width: 100%;
    background: linear-gradient(135deg, #ff416c, #ff4b2b);
    color: white;
    padding: 14px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: 0.3s;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    box-shadow: 0px 4px 8px rgba(255, 65, 108, 0.4);
}

/* Button Hover Effect */
.login-container button:hover {
    background: linear-gradient(135deg, #e63e62, #d7352b);
    transform: scale(1.05);
    box-shadow: 0px 6px 12px rgba(255, 65, 108, 0.5);
}

/* Additional Text */
.login-container p {
    margin-top: 15px;
    font-size: 14px;
    color: #333;
}

/* Link Styles */
.login-container a {
    color: #ff416c;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

/* Hover Effect for Links */
.login-container a:hover {
    text-decoration: underline;
    color: #d7352b;
}

/* Fade-In Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body>

    <div class="login-container">
        <h2>Login to Café Crepe</h2>
        <form action="login.php" method="POST">
            <input type="text" name="user_id" placeholder="User ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>

</body>
</html>
