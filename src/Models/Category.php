<?php
namespace App\Models;

use PDO;

class Category {
    private PDO $conn;
    private const CATEGORY_TABLE = "categories";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function read() {
        $query = "SELECT * FROM " . self::CATEGORY_TABLE;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function getCategoryCounts() {
        $query = "SELECT c.id, c.name, COUNT(p.id) as product_count
                  FROM ".self::CATEGORY_TABLE." c
                  LEFT JOIN products p ON c.id = p.category_id
                  GROUP BY c.id, c.name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}

