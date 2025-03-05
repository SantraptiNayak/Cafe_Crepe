<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php'; // Ensure this connects to the database

// Query to get booked rooms with food orders and their status
$query = "
    SELECT 
        b.room_number, 
        b.first_name, 
        b.last_name, 
        o.id AS order_id, 
        fo.id AS food_order_id,
        fo.item_name, 
        fo.quantity, 
        fo.total_price, 
        fo.status,  -- Added status field
        IFNULL(fo.order_date, 'No Order') AS order_date
    FROM bookings b
    LEFT JOIN orders o ON b.room_number = o.room_number
    LEFT JOIN food_orders fo ON o.id = fo.order_id
    WHERE b.check_out >= CURDATE()  -- Only show currently booked rooms
    ORDER BY b.room_number, fo.order_date DESC;
";

$result = $conn->query($query);

if (!$result) {
    die("SQL Error: " . $conn->error);
}

$bookedRooms = [];
while ($row = $result->fetch_assoc()) {
    $room = $row['room_number'];

    if (!isset($bookedRooms[$room])) {
        $bookedRooms[$room] = [
            'room_number' => $room,
            'guest_name' => $row['first_name'] . ' ' . $row['last_name'],
            'orders' => []
        ];
    }

    if ($row['order_id']) { // If an order exists
        $bookedRooms[$room]['orders'][] = [
            'food_order_id' => $row['food_order_id'],
            'item_name' => $row['item_name'],
            'quantity' => $row['quantity'],
            'total_price' => $row['total_price'],
            'order_date' => $row['order_date'],
            'status' => $row['status'] ?? 'Pending' // Ensure status is set
        ];
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booked Rooms & Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .dashboard-btn {
            background-color: #f1c40f;
            color: black;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .dashboard-btn:hover {
            background-color: #e1b307;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.8);
            color: black;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #2f3542;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.6);
        }
        tr:nth-child(odd) {
            background: rgba(255, 255, 255, 0.9);
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
        .deliver-btn {
            background-color: green;
            color: white;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .deliver-btn:hover {
            background-color: darkgreen;
        }
        .delivered {
            color: white;
            background-color: #27ae60;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
    <script>
        function deleteOrder(foodOrderId) {
            if (confirm("Are you sure you want to delete this order?")) {
                window.location.href = "delete_order.php?id=" + foodOrderId;
            }
        }
        
        function markDelivered(foodOrderId) {
            if (confirm("Mark this order as Delivered?")) {
                window.location.href = "deliver.php?id=" + foodOrderId;
            }
        }
    </script>
</head>
<body>

    <div class="top-bar">
        <h2>Booked Rooms & Food Orders</h2>
        <a href="admin_dashboard.php" class="dashboard-btn">Dashboard</a>
    </div>

    <table>
        <tr>
            <th>Room Number</th>
            <th>Guest Name</th>
            <th>Food Order</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Order Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($bookedRooms as $room) : ?>
            <tr>
                <td rowspan="<?= max(1, count($room['orders'])) ?>"><?= $room['room_number'] ?></td>
                <td rowspan="<?= max(1, count($room['orders'])) ?>"><?= $room['guest_name'] ?></td>
                <?php if (!empty($room['orders'])): ?>
                    <?php foreach ($room['orders'] as $index => $order): ?>
                        <?php if ($index > 0) echo '<tr>'; ?>
                        <td><?= htmlspecialchars($order['item_name']) ?></td>
                        <td><?= intval($order['quantity']) ?></td>
                        <td>₹<?= number_format((float)($order['total_price'] ?? 0), 2) ?></td>
                        <td><?= htmlspecialchars($order['order_date']) ?></td>
                        <td>
                            <?php if ($order['status'] == 'Delivered'): ?>
                                <span class="delivered">Delivered</span>
                            <?php else: ?>
                                <button class="deliver-btn" onclick="markDelivered(<?= $order['food_order_id'] ?>)">Mark as Delivered</button>
                            <?php endif; ?>
                        </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <td colspan="5">No Order</td>
                </tr>
                <?php endif; ?>
        <?php endforeach; ?>
    </table>

</body>
</html>
