<?php
namespace App;

use App\Controllers\CategoryController;
use App\Controllers\ProductController;
use App\Models\CategoryTreeBuilder;

class Router {
    public function route() {
        $categoryController = new CategoryController();
        $productController = new ProductController();
        
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
            case 'getProducts':
                    $sort_by = $_GET['sort_by'] ?? null;
                    $productController->getAll($sort_by);
                    break;
                case 'getProductsByCategory':
                    $category_id = $_GET['category_id'] ?? null;
                    $sort_by = $_GET['sort_by'] ?? null;
                    if ($category_id) {
                        $productController->getByCategory($category_id, $sort_by);
                    }
                    break;
                case 'sortProducts':
                    if (isset($_GET['sort_by'])) {
                        $productController->sort($_GET['sort_by']);
                    }
                    break;
                case 'getProduct':
                    if (isset($_GET['id'])) {
                        $productController->getProduct($_GET['id']);
                    }
                    break;
                case 'getCategoryCounts':
                    $categoryController->getCategoryCounts();
                    break;
                case 'getCategoryTree':
                    $treeBuilder = new CategoryTreeBuilder();
                    $treeBuilder->buildTree();
                    break;
                default:
                    http_response_code(404);
                    echo json_encode(["message" => "Action not found."]);
                    break;
            }
        } else {
            // За замовчуванням відображаємо categories.php
            $categoryController->index();
        }
    }
}
