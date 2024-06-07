<?php
namespace App\Models;

use App\Database;
use PDO;

class CategoryTreeBuilder {
    private PDO $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function buildTree() {
        $start_time = microtime(true);
        
        $query = "SELECT categories_id, parent_id FROM categories_tree";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $tree = $this->buildHierarchy($categories);
        
        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        
        header('Content-Type: application/json');
        echo json_encode(['tree' => $tree, 'execution_time' => $execution_time]);
    }
    
    private function buildHierarchy($categories) {
        $grouped = [];
        foreach ($categories as $category) {
            $grouped[$category['parent_id']][] = $category['categories_id'];
        }
        
        return $this->buildRecursive($grouped, 0);
    }
    
    private function buildRecursive($grouped, $parent) {
        $result = [];
        if (isset($grouped[$parent])) {
            foreach ($grouped[$parent] as $categoryId) {
                $result[$categoryId] = isset($grouped[$categoryId]) ? $this->buildRecursive($grouped, $categoryId) : $categoryId;
            }
        }
        return $result;
    }
}
