<?php
include 'db.php'; // Ensure database connection

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Sanitize ID

    // Update the order status to "Delivered"
    $query = "UPDATE food_orders SET status = 'Delivered' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>
                alert('Order marked as Delivered!');
                window.location.href = 'admin2.php';
              </script>";
    } else {
        echo "Error updating order status: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
