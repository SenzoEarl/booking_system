<?php
/**
 * book.php
 *
 * Shows the booking form for a service
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../classes/Database.php';

use BookingSystem\Classes\Database;

$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

$service = null;
if ($serviceId > 0) {
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $serviceId]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        $service = null;
    }
}

// Redirect back home only if the service truly does not exist
if (!$service) {
    header('Location: index.php');
    exit;
}

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book: <?= htmlspecialchars($service['name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Complete Your Booking</h1>
        <p><strong>Service:</strong> <?= htmlspecialchars($service['name']) ?>
            ($<?= number_format((float)$service['price'], 2) ?>)</p>
    </header>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form action="api/process_booking.php" method="POST">
        <input type="hidden" name="service_id" value="<?= (int)$service['id'] ?>">

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="booking_date">Appointment Date</label>
            <input type="date" id="booking_date" name="booking_date" min="<?= date('Y-m-d') ?>" required>
        </div>

        <div class="form-group">
            <label for="start_time">Start Time</label>
            <input type="time" id="start_time" name="start_time" required>
        </div>

        <button type="submit" class="btn" style="width: 100%;">Confirm Booking</button>
    </form>

    <p style="text-align: center; margin-top: 20px;">
        <a href="index.php">&larr; Back to Services</a>
    </p>
</div>
</body>
</html>