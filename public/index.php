<?php
/**
 * index.php
 *
 * Displays available services for the user to book
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Service.php';

use BookingSystem\Classes\Service;

$serviceModel = new Service();
$services = $serviceModel->getAllServices()

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <!-- Top Navigation Bar -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 20px;">
        <a href="admin.php" class="btn" style="background-color: #475569; font-size: 0.9rem; padding: 8px 14px;">Admin
            Dashboard</a>
    </div>

    <header>
        <h1>Select a Service</h1>
        <p>Choose from our available professional services below to schedule your appointment.</p>
    </header>

    <div class="card-grid">
        <?php if (empty($services)): ?>
            <p>No services are currently available.</p>
        <?php else: ?>
            <?php foreach ($services as $service): ?>
                <div class="card">
                    <div>
                        <h3><?= htmlspecialchars($service['name']) ?></h3>
                        <p><?= htmlspecialchars($service['description']) ?></p>
                        <p><small>Duration: <?= (int)$service['duration_minutes'] ?> minutes</small></p>
                    </div>
                    <div>
                        <div class="price">$<?= number_format((float)$service['price'], 2) ?></div>
                        <a href="book.php?service_id=<?= (int)$service['id'] ?>" class="btn">Book Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
