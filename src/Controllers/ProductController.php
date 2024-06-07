<?php
namespace App\Controllers;

use App\Database;
use App\Models\Product;
use PDO;

class ProductController {
    private ?PDO $db;
    private Product $product;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->product = new Product($this->db);
    }
    
    public function getAll($sort_by = null) {
        $stmt = $this->product->readAll();
        
        if ($sort_by) {
            $stmt = $this->product->sort($sort_by);
        }
        
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($products);
    }
    
    public function getByCategory($category_id, $sort_by = null) {
        $stmt = $this->product->readByCategory($category_id, $sort_by);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($products);
    }
    
    public function sort($sort_by) {
        $stmt = $this->product->sort($sort_by);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($products);
    }
    
    public function getProduct($id) {
        $stmt = $this->product->readOne($id);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode($product);
    }
}
