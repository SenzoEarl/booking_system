<?php
/**
 * User.php
 *
 * Manages customer retrieval and creation
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */

declare(strict_types=1);

namespace BookingSystem\Classes;

use PDO;

class User{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find existing user by email
     * Create one if user doesnt exist
     * @param string $name
     * @param string $email
     * @param string $phone
     *
     * @return int The user ID
     */
    public function findOrCreate(string $name, string $email, string $phone): int
    {
        // Check if user exists
        $statement = $this->db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $statement->execute(['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return (int)$user['id'];
        }

        // Create user if one is not found
        $insert_statement = $this->db->prepare("INSERT INTO users (name, email, phone) VALUES (:name, :email, :phone)");
        $insert_statement->execute(['name' => $name, 'email' => $email, 'phone' => $phone]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Get user details by ID
     *
     * @param int $id
     *
     * @return array|null
     */
    public function getUserById(int $id): ?array{
        $statement = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $statement->execute(['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user?: null;
    }
}

?>
