<?php
/**
 * cancel_booking.php
 *
 * Isolated endpoint to cancel an existing reservation.
 *
 * @package BookingSystem\Api
 * @author  Senzo Earl Maseko
 * @version 1.0.0
 */
declare(strict_types=1);

session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: ../admin.php');
    exit;
}

require_once __DIR__ . '/../../classes/Database.php';

use BookingSystem\Classes\Database;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../admin.php');
    exit;
}

$bookingId = isset($_POST['booking_id']) ? (int)$_POST['booking_id'] : 0;

if ($bookingId > 0) {
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = :id");
        $stmt->execute(['id' => $bookingId]);
    } catch (PDOException $e) {
        // Handle error silently or log
    }
}

header('Location: ../admin.php');
exit;