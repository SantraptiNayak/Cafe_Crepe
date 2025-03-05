<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total = mysqli_real_escape_string($conn, $_POST['total_amount']);
    $room = mysqli_real_escape_string($conn, $_POST['room_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $user_id = $_SESSION['user_id'];

    // Validate if room number matches a booked room
    $check_query = "SELECT * FROM bookings WHERE room_number=? AND email=?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "ss", $room, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // Insert order into orders table
        $order_query = "INSERT INTO orders (user_id, total_amount, room_number, email, phone) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $order_query);
        mysqli_stmt_bind_param($stmt, "idsss", $user_id, $total, $room, $email, $phone);
        if (!mysqli_stmt_execute($stmt)) {
            die("Error inserting into orders table: " . mysqli_error($conn));
        }

        $order_id = mysqli_insert_id($conn); // Get last inserted order ID

        // Check if order_id is valid
        if (!$order_id) {
            die("Order insertion failed, order_id is 0.");
        }

        // Check if cart is empty
        if (empty($_SESSION['cart'])) {
            die("Cart is empty, no food orders to insert.");
        }

        // Insert food items from cart into food_orders table using prepared statements
        $food_order_query = "INSERT INTO food_orders (order_id, user_id, food_name, quantity, total_price, room_number) 
                             VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $food_order_query);
        mysqli_stmt_bind_param($stmt, "iisidi", $order_id, $user_id, $food_name, $quantity, $total_price, $room);

        foreach ($_SESSION['cart'] as $item) {
            $food_name = $item['name'];
            $quantity = (int)$item['quantity'];
            $price = (float)$item['price'];
            $total_price = $price * $quantity; // Correct total price calculation

            // Execute the prepared statement for each item
            if (!mysqli_stmt_execute($stmt)) {
                die("Error inserting into food_orders: " . mysqli_error($conn));
            }
        }

        // Clear the cart after successful order
        unset($_SESSION['cart']);

        echo "<script>alert('Order Confirmed!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('No booking found for this room number and email. Please check your details!');</script>";
    }
}
?>
