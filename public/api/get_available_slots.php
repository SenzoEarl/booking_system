<?php
/**
 * get_available_slots.php
 *
 * Returns booking availability or slot details in JSON format if requested via frontend JS.
 *
 * @package BookingSystem\Api
 * @author  Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

header('Content-Type: application/json');

require_once __DIR__ . '/../../classes/Database.php';
require_once __DIR__ . '/../../classes/Booking.php';

use BookingSystem\Classes\Booking;

$date = $_GET['date'] ?? date('Y-m-d');
$serviceId = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

$response = [
    'date' => $date,
    'service_id' => $serviceId,
    'status' => 'active'
];

echo json_encode($response);
exit;