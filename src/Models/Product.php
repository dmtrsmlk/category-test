<?php
namespace App\Models;

use PDO;

class Product {
    private PDO $conn;
    private const PRODUCT_TABLE = "products";
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function readByCategory($category_id, $sort_by = null) {
        $query = "SELECT * FROM " . self::PRODUCT_TABLE . " WHERE category_id = ?";
        if ($sort_by) {
            $query .= " ORDER BY " . $sort_by;
        }
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $category_id);
        $stmt->execute();
        return $stmt;
    }
    
    public function sort($sort_by) {
        $query = "SELECT * FROM " . self::PRODUCT_TABLE . " ORDER BY " . $sort_by;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    public function readOne($id) {
        $query = "SELECT * FROM " . self::PRODUCT_TABLE . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        return $stmt;
    }
    
    public function readAll() {
        $query = "SELECT * FROM " . self::PRODUCT_TABLE;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
