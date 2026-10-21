<?php
/**
 * process_booking.php
 *
 * Isolated endpoint handling form submissions, creating users, and storing bookings.
 *
 * @package BookingSystem\api
 * @author  Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Service.php';
require_once __DIR__ . '/../../classes/User.php';
require_once __DIR__ . '/../../classes/Booking.php';

use BookingSystem\Classes\User;
use BookingSystem\Classes\Booking;

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Sanitize and collect input data
$serviceId = isset($_POST['service_id']) ? (int)$_POST['service_id'] : 0;
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$bookingDate = trim($_POST['booking_date'] ?? '');
$startTime = trim($_POST['start_time'] ?? '');

// Basic validation
if ($serviceId <= 0 || empty($name) || empty($email) || empty($phone) || empty($bookingDate) || empty($startTime)) {
    header('Location: ../book.php?service_id=' . $serviceId . '&error=' . urlencode('All fields are required.'));
    exit;
}

try {
    // 1. Find or create user
    $userModel = new User();
    $userId = $userModel->findOrCreate($name, $email, $phone);

    // 2. Create booking
    $bookingModel = new Booking();
    $bookingId = $bookingModel->createBooking($serviceId, $userId, $bookingDate, $startTime . ':00');

    // Redirect to confirmation or success view
    header('Location: ../confirmation.php?booking_id=' . $bookingId);
    exit;

} catch (Exception $e) {
    // Handle overlapping slots or database issues
    header('Location: ../book.php?service_id=' . $serviceId . '&error=' . urlencode($e->getMessage()));
    exit;
}