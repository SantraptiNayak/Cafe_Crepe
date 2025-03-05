<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

include 'db.php'; // Database connection

$total = isset($_GET['total']) ? (float)$_GET['total'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);

    // Check if the room is booked and matches the given email
    $check_query = "SELECT * FROM bookings WHERE room_number='$room_number' AND email='$email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // Fetch user details
        $user_query = "SELECT first_name, last_name FROM users WHERE user_id='$user_id'";
        $user_result = mysqli_query($conn, $user_query);
        $user_data = mysqli_fetch_assoc($user_result);
        $first_name = $user_data['first_name'] ?? '';
        $last_name = $user_data['last_name'] ?? '';

        // Insert into orders table
        $sql = "INSERT INTO orders (user_id, first_name, last_name, room_number, total_amount) 
                VALUES ('$user_id', '$first_name', '$last_name', '$room_number', '$total_amount')";

        if (mysqli_query($conn, $sql)) {
            $order_id = mysqli_insert_id($conn); // Get last inserted order ID

            // Insert food orders if the cart is not empty
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $item_name = mysqli_real_escape_string($conn, $item['name']);
                    $quantity = (int)$item['quantity'];
                    $total_price = (float)$item['price'];

                    $sql_food_order = "INSERT INTO food_orders (order_id, item_name, quantity, total_price, room_number) 
                    VALUES ('$order_id', '$item_name', '$quantity', '$total_price', '$room_number')";
                    mysqli_query($conn, $sql_food_order);

                }
            }

            echo "<script>
                    setTimeout(function() {
                        alert('Order Confirmed!');
                        window.location.href = 'index.php';
                    }, 500);
                  </script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('No booking found for this room number and email. Please check your details!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Café Crepe</title>
    <style>
        /* Global Reset & Font */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

/* Full-Screen Centered Layout */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, #ff758c, #ff7eb3, #fad0c4);
}

/* Checkout Container */
.checkout-container {
    background: rgba(255, 255, 255, 0.95); /* Glass Effect */
    padding: 35px;
    border-radius: 15px;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.25);
    text-align: center;
    max-width: 420px;
    width: 100%;
    backdrop-filter: blur(10px);
    border: 2px solid rgba(255, 255, 255, 0.3);
    animation: fadeIn 0.8s ease-in-out;
}

/* Checkout Title */
.checkout-container h2 {
    margin-bottom: 10px;
    font-size: 24px;
    font-weight: bold;
    color: #ff416c;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-shadow: 2px 2px 5px rgba(255, 65, 108, 0.3);
}

/* Subtitle */
.checkout-container h4 {
    color: #ff758c;
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 500;
}

/* Input Fields */
.checkout-container input {
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
.checkout-container input:focus {
    border-color: #ff416c;
    outline: none;
    box-shadow: 0px 0px 12px rgba(255, 65, 108, 0.5);
    background: white;
}

/* Submit Button with Hover Effect */
.checkout-container button {
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
.checkout-container button:hover {
    background: linear-gradient(135deg, #e63e62, #d7352b);
    transform: scale(1.05);
    box-shadow: 0px 6px 12px rgba(255, 65, 108, 0.5);
}

/* Decorative Floating Effect */
.checkout-container::after {
    content: "";
    position: absolute;
    top: -10px;
    left: -10px;
    width: 100%;
    height: 100%;
    border-radius: 15px;
    background: linear-gradient(135deg, rgba(255, 65, 108, 0.2), rgba(255, 255, 255, 0));
    z-index: -1;
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
    <div class="checkout-container">
        <h2>Checkout</h2>
        <h4>Total Amount: $<?php echo $total; ?></h4>
        <form action="checkout.php" method="POST">
            <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
            <label>Room Number:</label>
            <input type="text" name="room_number" required>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Phone Number:</label>
            <input type="text" name="phone" required>
            <button type="submit">Confirm Order</button>
        </form>
    </div>
</body>
</html>
