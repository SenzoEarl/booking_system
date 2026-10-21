<?php
/**
 * admin.php
 *
 * Admin dashboard displaying all reservation records.
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */


declare(strict_types=1);

session_start();

// Simple Admin Password (change as needed)
$adminPassword = 'admin123';

// Handle Login Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_password'])) {
    if ($_POST['admin_password'] === $adminPassword) {
        $_SESSION['is_admin'] = true;
    } else {
        $loginError = "Incorrect password.";
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    unset($_SESSION['is_admin']);
    header('Location: admin.php');
    exit;
}

// If not logged in, show password prompt
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true):
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <div class="container" style="max-width: 400px; margin-top: 100px;">
        <header>
            <h1>Admin Login</h1>
            <p>Please enter your password to access the dashboard.</p>
        </header>

        <?php if (isset($loginError)): ?>
            <div class="alert alert-error"><?= $loginError ?></div>
        <?php endif; ?>

        <form action="admin.php" method="POST">
            <div class="form-group">
                <label for="admin_password">Password</label>
                <input type="password" id="admin_password" name="admin_password" required>
            </div>
            <button type="submit" class="btn" style="width: 100%;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php">&larr; Back to Public Site</a>
        </p>
    </div>
    </body>
    </html>
    <?php
    exit;
endif;

// --- LOGGED IN ADMIN DASHBOARD ---
require_once __DIR__ . '/../classes/Database.php';
require_once __DIR__ . '/../classes/Booking.php';

use BookingSystem\Classes\Booking;

$bookingModel = new Booking();
$bookings = $bookingModel->getAllBookingsWithDetails();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Bookings</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid var(--border-color);
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #f1f5f9;
            font-weight: 600;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .badge-confirmed {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .badge-cancelled {
            background-color: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body>
<div class="container" style="max-width: 1100px;">
    <header style="display: flex; justify-content: space-between; align-items: center; text-align: left; margin-bottom: 20px;">
        <div>
            <h1>Admin Dashboard</h1>
            <p>Manage and review all system appointments.</p>
        </div>
        <div>
            <a href="index.php" class="btn" style="background-color: #475569;">Public Site</a>
            <a href="admin.php?logout=true" class="btn" style="background-color: #dc2626; margin-left: 10px;">Logout</a>
        </div>
    </header>

    <?php if (empty($bookings)): ?>
        <div class="card" style="text-align: center; padding: 40px;">
            <p>No bookings have been made yet.</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td>#<?= (int)$b['booking_id'] ?></td>
                        <td><strong><?= htmlspecialchars($b['user_name']) ?></strong></td>
                        <td><?= htmlspecialchars($b['service_name']) ?>
                            <br><small>($<?= number_format((float)$b['price'], 2) ?>)</small></td>
                        <td><?= htmlspecialchars($b['booking_date']) ?>
                            <br><small><?= htmlspecialchars(substr($b['start_time'], 0, 5)) ?>
                                (<?= (int)$b['duration_minutes'] ?>m)</small></td>
                        <td><?= htmlspecialchars($b['user_email']) ?>
                            <br><small><?= htmlspecialchars($b['user_phone']) ?></small></td>
                        <td>
                                    <span class="badge badge-<?= htmlspecialchars($b['status']) ?>">
                                        <?= htmlspecialchars($b['status']) ?>
                                    </span>
                        </td>
                        <td>
                            <?php if ($b['status'] === 'confirmed'): ?>
                                <form action="api/cancel_booking.php" method="POST"
                                      style="padding: 0; background: none; border: none; margin: 0;">
                                    <input type="hidden" name="booking_id" value="<?= (int)$b['booking_id'] ?>">
                                    <button type="submit" class="btn btn-cancel"
                                            style="background-color: #dc2626; padding: 6px 10px; font-size: 0.8rem;">
                                        Cancel
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="color: #94a3b8; font-size: 0.85rem;">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>