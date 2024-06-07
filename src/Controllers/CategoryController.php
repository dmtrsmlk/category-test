<?php
namespace App\Controllers;

use App\Database;
use App\Models\Category;
use App\Models\Product;
use PDO;

class CategoryController {
    private ?PDO $db;
    private Category $category;
    private Product $product;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->category = new Category($this->db);
        $this->product = new Product($this->db);
    }
    
    public function index(): void {
        $stmt = $this->category->read();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmtCounts = $this->category->getCategoryCounts();
        $categoryCounts = $stmtCounts->fetchAll(PDO::FETCH_ASSOC);
        
        $categoriesWithCounts = [];
        $totalProducts = 0;
        foreach ($categories as $category) {
            $categoryId = $category['id'];
            $categoryCount = 0;
            foreach ($categoryCounts as $count) {
                if ($count['id'] == $categoryId) {
                    $categoryCount = $count['product_count'];
                    $totalProducts += $categoryCount;
                    break;
                }
            }
            $categoriesWithCounts[] = [
                'id' => $category['id'],
                'name' => $category['name'],
                'product_count' => $categoryCount,
            ];
        }
        
        // Передаємо всі категорії і кількість продуктів
        $stmtProducts = $this->product->readAll();
        $allProducts = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);
        
        require __DIR__ . '/../Views/categories.php';
    }
    
    public function getCategoryCounts(): void {
        $stmt = $this->category->getCategoryCounts();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($categories);
    }
}
