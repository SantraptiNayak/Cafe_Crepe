


<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
    exit();
}

include 'db.php';

// Function to get available rooms based on bed type and date range
function getAvailableRooms($conn, $bed_type, $checkin, $checkout) {
    $available_rooms = [];

    $query = "SELECT room_number FROM rooms 
              WHERE bed_type = '$bed_type' 
              AND room_number NOT IN (
                  SELECT room_number FROM bookings 
                  WHERE ('$checkin' BETWEEN check_in AND check_out OR '$checkout' BETWEEN check_in AND check_out)
              )";

    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $available_rooms[] = $row['room_number'];
        }
    }
    return $available_rooms;
}

// Booking logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $bed_type = $_POST['bed_type'];
    $room_number = $_POST['room_number'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $today = date("Y-m-d");

    // Validate phone number (10 digits only)
    if (!preg_match("/^[0-9]{10}$/", $phone)) {
        echo "<script>alert('Invalid phone number! Must be 10 digits.'); window.history.back();</script>";
        exit();
    }

    // Validate dates (Prevent past booking & invalid checkout)
    if ($checkin < $today || $checkout < $today) {
        echo "<script>alert('Invalid date! You cannot book past dates.'); window.history.back();</script>";
        exit();
    }
    if ($checkout <= $checkin) {
        echo "<script>alert('Check-out date must be after check-in date.'); window.history.back();</script>";
        exit();
    }

    // Ensure room is still available before booking
    $available_rooms = getAvailableRooms($conn, $bed_type, $checkin, $checkout);
    if (!in_array($room_number, $available_rooms)) {
        echo "<script>alert('Error: Selected room is no longer available.'); window.history.back();</script>";
        exit();
    }

    // Insert booking
    $sql = "INSERT INTO bookings (user_id, first_name, middle_name, last_name, phone_number, email, bed_type, room_number, check_in, check_out) 
            VALUES ('$user_id', '$first_name', '$middle_name', '$last_name', '$phone', '$email', '$bed_type', '$room_number', '$checkin', '$checkout')";

    if (mysqli_query($conn, $sql)) {
        // Mark room as booked
        $update_sql = "UPDATE rooms SET status = 'Booked' WHERE room_number = '$room_number'";
        mysqli_query($conn, $update_sql);
        
        echo "<script>alert('Booking Confirmed!'); window.location.href = 'index.php';</script>";
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
    <title>Book a Room | Hotel Lux</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="booking.css">
</head>
<body>
    <div class="booking-container">
        <h2>Book Your Stay</h2>
        <form action="booking.php" method="POST">
            <input type="text" name="first_name" placeholder="First Name (Required)" required>
            <input type="text" name="middle_name" placeholder="Middle Name (Optional)">
            <input type="text" name="last_name" placeholder="Last Name (Required)" required>
            <input type="text" name="phone" placeholder="Phone Number (10 digits)" required pattern="[0-9]{10}">
            <input type="email" name="email" placeholder="Email ID" required>

            <select name="bed_type" id="bed_type" required>
                <option value="" disabled selected>Select Bed Type</option>
                <option value="Single">Single</option>
                <option value="Double">Double</option>
                <option value="King">King</option>
            </select>

            <input type="date" name="checkin" id="checkin" required>
            <input type="date" name="checkout" id="checkout" required>

            <select name="room_number" id="room_number" required>
                <option value="" disabled selected>Select Room Number</option>
            </select>

            <button type="submit">Book Now</button>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            let today = new Date().toISOString().split('T')[0]; 
            $("#checkin, #checkout").attr("min", today);

            $("#checkin").on("change", function() {
                $("#checkout").attr("min", $(this).val());
            });

            function fetchRooms() {
                let bed_type = $("#bed_type").val();
                let checkin = $("#checkin").val();
                let checkout = $("#checkout").val();
                
                if (bed_type && checkin && checkout) {
                    $.ajax({
                        url: "fetch_rooms.php",
                        method: "POST",
                        data: { bed_type: bed_type, checkin: checkin, checkout: checkout },
                        success: function(response) {
                            $("#room_number").html(response);
                        }
                    });
                }
            }
            $("#bed_type, #checkin, #checkout").on("change", fetchRooms);
        });
    </script>
    <script>
    $(document).ready(function() {
        let today = new Date().toISOString().split('T')[0]; 
        $("#checkin, #checkout").attr("min", today);

        $("#checkin").on("change", function() {
            $("#checkout").attr("min", $(this).val());
        });

        function fetchRooms() {
            let bed_type = $("#bed_type").val();
            let checkin = $("#checkin").val();
            let checkout = $("#checkout").val();

            if (bed_type && checkin && checkout) {
                $.ajax({
                    url: "fetch_rooms.php",
                    method: "POST",
                    data: { bed_type: bed_type, checkin: checkin, checkout: checkout },
                    success: function(response) {
                        $("#room_number").html(response);
                    }
                });
            }
        }
        $("#bed_type, #checkin, #checkout").on("change", fetchRooms);

        // Name validation before form submission
        $("form").on("submit", function(event) {
            let firstName = $("input[name='first_name']").val().trim();
            let lastName = $("input[name='last_name']").val().trim();
            let namePattern = /^[A-Za-z]+$/;

            if (!namePattern.test(firstName)) {
                alert("First name must contain only alphabets!");
                event.preventDefault();
                return false;
            }
            if (!namePattern.test(lastName)) {
                alert("Last name must contain only alphabets!");
                event.preventDefault();
                return false;
            }
        });
    });
</script>

</body>
</html>