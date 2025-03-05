<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Validate User ID (must start with an alphabet)
    if (!preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $user_id)) {
        echo "<script>alert('User ID must start with an alphabet and contain only letters and numbers!'); window.location='signup.php';</script>";
        exit();
    }

    // Validate First Name & Last Name (only alphabets allowed)
    if (!preg_match("/^[a-zA-Z]+$/", $first_name) || !preg_match("/^[a-zA-Z]+$/", $last_name)) {
        echo "<script>alert('First Name and Last Name should only contain alphabets!'); window.location='signup.php';</script>";
        exit();
    }

    // Check if User ID already exists
    $check_query = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $check_query);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('User ID already taken! Please choose another.'); window.location='signup.php';</script>";
        exit();
    }

    // Insert user into the database
    $query = "INSERT INTO users (email, password, user_id, first_name, last_name) 
              VALUES ('$email', '$password', '$user_id', '$first_name', '$last_name')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Café Crepe</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* Centering the Form */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #ff758c, #ff7eb3, #fad0c4);
}

/* Signup Container */
.signup-container {
    background: rgba(255, 255, 255, 0.95); /* Soft Glassmorphism Effect */
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

/* Title */
.signup-container h2 {
    margin-bottom: 10px;
    font-size: 26px;
    font-weight: bold;
    color: #ff416c;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 2px 2px 5px rgba(255, 65, 108, 0.3);
}

/* Input Fields */
.signup-container input {
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
.signup-container input:focus {
    border-color: #ff416c;
    outline: none;
    box-shadow: 0px 0px 12px rgba(255, 65, 108, 0.5);
    background: white;
}

/* Signup Button */
.signup-container button {
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
.signup-container button:hover {
    background: linear-gradient(135deg, #e63e62, #d7352b);
    transform: scale(1.05);
    box-shadow: 0px 6px 12px rgba(255, 65, 108, 0.5);
}

/* Additional Text */
.signup-container p {
    margin-top: 15px;
    font-size: 14px;
    color: #333;
}

/* Link Styles */
.signup-container a {
    color: #ff416c;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}

/* Hover Effect for Links */
.signup-container a:hover {
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
    <script>
        function validateForm() {
            let userId = document.forms["signupForm"]["user_id"].value;
            let firstName = document.forms["signupForm"]["first_name"].value;
            let lastName = document.forms["signupForm"]["last_name"].value;

            // User ID Validation: Must start with an alphabet
            let userIdPattern = /^[a-zA-Z][a-zA-Z0-9]*$/;
            if (!userIdPattern.test(userId)) {
                alert("User ID must start with an alphabet and contain only letters and numbers!");
                return false;
            }

            // Name Validation: Only alphabets allowed
            let namePattern = /^[a-zA-Z]+$/;
            if (!namePattern.test(firstName) || !namePattern.test(lastName)) {
                alert("First Name and Last Name should only contain alphabets!");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>

    <div class="signup-container">
        <h2>Create Your Account</h2>
        <form name="signupForm" action="signup.php" method="POST" onsubmit="return validateForm()">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="user_id" placeholder="User ID" required>
            <input type="text" name="first_name" placeholder="First Name" required>
            <input type="text" name="last_name" placeholder="Last Name" required>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

</body>
</html>
