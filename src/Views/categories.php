<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories and Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.min.js"></script>
    <script src="app.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h2>Categories</h2>
            <ul id="categories" class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="#" class="category-link" data-id="all">All Categories</a>
                    <span class="badge badge-primary badge-pill"><?= count($allProducts) ?></span>
                </li>
                <?php foreach ($categoriesWithCounts as $category): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <a href="#" class="category-link" data-id="<?= $category['id'] ?>"><?= $category['name'] ?></a>
                        <span class="badge badge-primary badge-pill"><?= $category['product_count'] ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <button id="showTreeBtn" class="btn btn-info mt-3">Show Category Tree</button>
        </div>
        <div class="col-md-9">
            <h2>Products</h2>
            <div class="form-group">
                <select id="sort" class="form-control">
                    <option value="price ASC">Cheapest First</option>
                    <option value="name ASC">Alphabetical</option>
                    <option value="created_at DESC">Newest First</option>
                </select>
            </div>
            <div id="products" class="row">
                <?php foreach ($allProducts as $product): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                                <p class="card-text">$<?= htmlspecialchars($product['price']) ?></p>
                                <button class="btn btn-primary buy-button" data-id="<?= $product['id'] ?>">Купити</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div id="productModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
