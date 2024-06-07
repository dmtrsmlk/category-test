<?php
namespace App;

use PDO;
use PDOException;

class Database {
    private const HOST = 'mariadb';
    private const DB_NAME = 'test-db';
    private const USER = 'root';
    private const PASSWORD = 'root';
    public ?PDO $conn;
    
    public function getConnection(): ?PDO
    {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . self::HOST .
                ";dbname=" . self::DB_NAME,
                self::USER,
                self::PASSWORD
            );
            
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

