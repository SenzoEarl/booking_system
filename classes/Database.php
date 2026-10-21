<?php
/**
 * database.php
 *
 * Handles secure database connections using PDO as well as the Singleton pattern
 *
 *
 * @package BookingSystem\Classes
 * @author Senzo Earl Maseko
 * @version 1.0.0
 */


declare(strict_types=1);

namespace BookingSystem\Classes;

use PDO;
use PDOException;

class Database {
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    //Database Variables
    private string $host = '127.0.0.1'; // Using 127.0.0.1 forces TCP/IP instead of socket ambiguity
    private string $port = '3306';
    private string $databaseName = 'booking_system';
    private string $username = 'booker';
    private string $password = 'booker1';
    private string $charset = 'utf8mb4';

    /**
     * Singleton pattern
     * use a private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->databaseName};charset={$this->charset}";
        $options = [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
          PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try{
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            throw new PDOException("Database connection error" . $e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Retrieves the single instance of the Database class.
     *
     * @return Database
     */
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Prevents the unserialization of instance
     * @return PDO
     */

    public function getConnection(): PDO {
        return $this->connection;
    }

    /**
     * Prevent instance prematurely cloning
     *
     */
    private function __clone() {}

    /**
     * Prevent the unserialization of the instance
     *
     */
    public function __wakeup() {}
}

?>
