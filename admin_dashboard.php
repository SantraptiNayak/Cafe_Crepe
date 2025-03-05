<?php
session_start();
include('db.php'); // Ensure database connection

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle clearing a booking
if (isset($_POST['clear_booking'])) {
    $room_number = $_POST['room_number'];

    // Clear the booking
    $clear_booking_query = "DELETE FROM bookings WHERE room_number = ?";
    $stmt = $conn->prepare($clear_booking_query);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();
    $stmt->close();

    header("Location: admin_dashboard.php");
    exit();
}

// Date filtering
$selected_date = date('Y-m-d'); // Default to today's date
if (isset($_POST['date_filter'])) {
    $selected_date = $_POST['selected_date'];
}

// Fetch rooms and bookings
$rooms_query = "
   SELECT 
    r.room_number, 
    r.bed_type, 
    COALESCE(b.check_in, 'N/A') AS check_in, 
    COALESCE(b.check_out, 'N/A') AS check_out, 
    COALESCE(b.phone_number, 'N/A') AS phone_number,  
    CASE 
        WHEN b.room_number IS NOT NULL 
             AND ('$selected_date' BETWEEN b.check_in AND b.check_out) 
        THEN 'Booked'
        ELSE 'Free'
    END AS booking_status,
    COALESCE(b.first_name, 'N/A') AS first_name, 
    COALESCE(b.last_name, 'N/A') AS last_name
FROM rooms r
LEFT JOIN bookings b ON r.room_number = b.room_number 
WHERE ('$selected_date' BETWEEN b.check_in AND b.check_out OR b.room_number IS NULL)
GROUP BY r.room_number, b.check_in, b.check_out, b.phone_number, b.first_name, b.last_name
ORDER BY r.room_number";

$rooms_result = mysqli_query($conn, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background: linear-gradient(120deg, #2980b9, #8e44ad);
        color: white;
    }
    .container {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    }
    h2 {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .table {
        background: rgba(191, 185, 204, 0.8);
        color: black;
    }
    .table th {
        background-color: #2f3542;
        color: white;
        font-weight: bold;
    }
    .table td {
        color: black;
    }
    .btn-primary {
        background-color: #ff4757;
        border: none;
    }
    .btn-warning {
        background-color: #ffa502;
        border: none;
    }
    .btn-danger {
        background-color: #e84118;
    }
    .btn-dark {
        background-color: #2f3542;
    }
</style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard - Room Bookings</h2>

    <!-- Date & Filter Options -->
    <form method="POST" class="form-inline justify-content-center mb-3">
        <label for="selected_date" class="mr-2">Filter by Date:</label>
        <input type="date" name="selected_date" id="selected_date" class="form-control mr-2" value="<?= $selected_date ?>">
        <button type="submit" name="date_filter" class="btn btn-primary mr-2">Apply</button>
        <a href="admin2.php" class="btn btn-info">Booked Rooms & Orders</a>
    </form>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Room No</th>
                <th>Bed Type</th>
                <th>Booking Dates</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($room = mysqli_fetch_assoc($rooms_result)) { ?>
                <tr>
                    <td><?= htmlspecialchars($room['room_number']); ?></td>
                    <td><?= htmlspecialchars($room['bed_type']); ?></td>
                    <td>
                        <?= isset($room['check_in']) ? htmlspecialchars($room['check_in'] . " to " . $room['check_out']) : 'Free'; ?>
                    </td>
                    <td><?= !empty($room['phone_number']) ? htmlspecialchars($room['phone_number']) : 'N/A'; ?></td>
                    <td>
                        <?php if (isset($room['check_in'])) { ?>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="room_number" value="<?= $room['room_number']; ?>">
                                <button type="submit" name="clear_booking" class="btn btn-danger btn-sm">Clear</button>
                            </form>
                        <?php } else { ?>
                            <button class="btn btn-secondary btn-sm" disabled>NA</button>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Buttons for navigation -->
    <div class="d-flex justify-content-between">
        <a href="logout.php" class="btn btn-dark">Logout</a>
        
    </div>
</div>

</body>
</html>
