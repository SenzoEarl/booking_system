<?php
/**
 * Service.php
 *
 * Manages operations related to booking system/services
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

namespace BookingSystem\Classes;

use PDO;

class Service{
    private PDO $db;

    /**
     * Initialize the database connection
     */
    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve all available services
     *
     * @return array
     */
    public function getAllServices(): array
    {
        $statement = $this->db->query("SELECT * FROM services ORDER BY name ASC");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single service by the ID
     *
     * @param int $id
     * @return array|null
     */
    public function getServiceById(int $id): ?array
    {
        $statement = $this->db->prepare("SELECT * FROM services WHERE id = :id LIMIT 1");
        $statement->execute(['id' => $id]);
        $service = $statement->fetch(PDO::FETCH_ASSOC);

        return $service?: null;

    }
}

?>
