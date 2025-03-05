<?php
include 'db.php';

if (isset($_POST['bed_type']) && isset($_POST['checkin']) && isset($_POST['checkout'])) {
    $bed_type = $_POST['bed_type'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    $query = "SELECT room_number FROM rooms 
              WHERE bed_type = '$bed_type' 
              AND room_number NOT IN (
                  SELECT room_number FROM bookings 
                  WHERE ('$checkin' BETWEEN check_in AND check_out OR '$checkout' BETWEEN check_in AND check_out)
              )";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['room_number'] . "'>" . $row['room_number'] . "</option>";
    }
}
?>
