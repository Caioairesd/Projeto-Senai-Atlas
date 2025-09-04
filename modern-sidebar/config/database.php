<?php
/**
 * Database Configuration
 * Hardware Inventory Management System
 */

class Database {
    private $host = 'localhost';
    private $db_name = 'hardware_inventory';
    private $username = 'root';
    private $password = '';
    private $conn;

    /**
     * Get database connection
     */
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
                )
            );
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    /**
     * Execute SQL file for database setup
     */
    public function executeSQLFile($filepath) {
        try {
            $sql = file_get_contents($filepath);
            $statements = explode(';', $sql);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $this->conn->exec($statement);
                }
            }
            return true;
        } catch(PDOException $exception) {
            echo "SQL execution error: " . $exception->getMessage();
            return false;
        }
    }
}

/**
 * Database connection helper function
 */
function getDBConnection() {
    $database = new Database();
    return $database->getConnection();
}
?>
