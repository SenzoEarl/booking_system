<?php
/**
 * Booking.php
 *
 * Handles scheduling, validations & reservations
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

namespace BookingSystem\Classes;

use PDO;
use Exception;

class Booking{
    private PDO $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Check if timeslot is available for given time and date
     *
     * @param string $date Format YYYY-MM-DD
     * @param string $startTime Format HH:MM:SS
     * @param int $durationMinutes
     *
     * @return bool True if available, false if theres a conflict
     *
     */
    public function isSlotAvailable(string $date, string $startTime, int $durationMinutes): bool{
        // overlap logic: check booking on same timeslot
        $statement = $this->db->prepare("
            SELECT b.id, b.start_time, s.duration_minutes 
            FROM bookings b
            JOIN services s ON b.service_id = s.id
            WHERE b.booking_date = :booking_date 
              AND b.status = 'confirmed'
        ");
        $statement->execute(['booking_date' => $date]);
        $existing_bookings = $statement->fetchAll(PDO::FETCH_ASSOC);

        $newStartTimeStamp = strtotime("$date $startTime");
        $newEndTimeStamp = $newStartTimeStamp + ($durationMinutes * 60);

        foreach ($existing_bookings as $booking){
            $existingStart = strtotime("$date " . $booking['start_time']);
            $existingEnd = $existingStart + ((int)$booking['duration_minutes'] * 60);

            //check overlap
            if ($newStartTimeStamp < $existingEnd && $newEndTimeStamp > $existingStart){
                return false; //conflict detected
            }
        }

        return true;

    }

    /**
     * Create a new booking record
     *
     * @param int $serviceId
     * @param int $userId
     * @param string $date
     * @param string $startTime
     * @return int Booking ID
     * @thows Exception if slot is taken or invalid
     */
    public function createBooking(int $serviceId, int $userId, string $date, string $startTime): int
    {
        // Fetch service to check duration
        $serviceModel = new Service();
        $service = $serviceModel->getServiceById($serviceId);

        if (!$service){
            throw new Exception('Service not found. Are you sure it exists?');
        }

        // Validate avaliablity
        if (!$this->isSlotAvailable($date, $startTime, (int)$service['duration_minutes'])){
            throw new Exception('Slot is unavailable. PLease choose another time');
        }

        $statement = $this->db->prepare("INSERT INTO bookings (service_id, user_id, booking_date, start_time, status) VALUES (:service_id, :user_id, :booking_date, :start_time, 'confirmed')");
        $statement->execute([
            'service_id' => $serviceId,
            'user_id' => $userId,
            'booking_date' => $date,
            'start_time' => $startTime
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Get all booking with user and service details
     * For admin and dashboard
     *
     * @return array
     */
    public function getAllBookingsWithDetails(): array
    {
        $statement = $this->db->query("SELECT b.id AS booking_id, b.booking_date, b.start_time, b.status, b.created_at, s.name AS service_name, s.duration_minutes, s.price, u.name AS user_name, u.email AS user_email, u.phone AS user_phone FROM bookings b JOIN services s ON b.service_id = s.id JOIN users u ON b.user_id = u.id ORDER BY b.booking_date DESC, b.start_time DESC");

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
