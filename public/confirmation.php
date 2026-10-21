<?php
/**
 * confirmation.php
 *
 * Displays the confirmation details for a successfully completed booking.
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */


declare(strict_types=1);

require_once __DIR__ . '/../classes/Database.php';

use BookingSystem\Classes\Database;

$bookingId = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;
$bookingDetails = null;

if ($bookingId > 0) {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->prepare("
        SELECT 
            b.id AS booking_id,
            b.booking_date,
            b.start_time,
            b.status,
            b.created_at,
            s.name AS service_name,
            s.description AS service_description,
            s.duration_minutes,
            s.price,
            u.name AS user_name,
            u.email AS user_email,
            u.phone AS user_phone
        FROM bookings b
        JOIN services s ON b.service_id = s.id
        JOIN users u ON b.user_id = u.id
        WHERE b.id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $bookingId]);
    $bookingDetails = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$bookingDetails) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container" style="max-width: 600px;">
    <header>
        <div class="alert alert-success" style="font-size: 1.1rem; margin-bottom: 20px;">
            <strong>Success!</strong> Your appointment has been successfully booked.
        </div>
        <h1>Booking Confirmation</h1>
        <p>Reference ID: #<?= (int)$bookingDetails['booking_id'] ?></p>
    </header>

    <div class="card" style="margin-bottom: 20px;">
        <h3>Appointment Details</h3>
        <p><strong>Service:</strong> <?= htmlspecialchars($bookingDetails['service_name']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($bookingDetails['service_description']) ?></p>
        <p><strong>Date:</strong> <?= htmlspecialchars($bookingDetails['booking_date']) ?></p>
        <p><strong>Time:</strong> <?= htmlspecialchars(substr($bookingDetails['start_time'], 0, 5)) ?>
            (<?= (int)$bookingDetails['duration_minutes'] ?> mins)</p>
        <p><strong>Price:</strong> $<span class="price"
                                          style="font-size: 1rem;"><?= number_format((float)$bookingDetails['price'], 2) ?></span>
        </p>
    </div>

    <div class="card" style="margin-bottom: 30px;">
        <h3>Client Information</h3>
        <p><strong>Name:</strong> <?= htmlspecialchars($bookingDetails['user_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($bookingDetails['user_email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($bookingDetails['user_phone']) ?></p>
    </div>

    <div style="text-align: center;">
        <a href="index.php" class="btn">Book Another Appointment</a>
        <a href="admin.php" class="btn" style="background-color: #475569; margin-left: 10px;">View All Bookings
            (Admin)</a>
    </div>
</div>
</body>
</html>