$(document).ready(function() {
    loadCategories();
    var categoryId = getUrlParameter('category_id') || 'all';
    var sortBy = getUrlParameter('sort_by') || 'created_at DESC';
    loadProducts(categoryId, sortBy);

    $('#categories').on('click', '.category-link', function(e) {
        e.preventDefault();
        var categoryId = $(this).data('id');
        var sortBy = $('#sort').val();
        loadProducts(categoryId, sortBy);
        updateUrl('category_id', categoryId);
        updateUrl('sort_by', sortBy);
    });

    $('#sort').change(function() {
        var sortBy = $(this).val();
        var categoryId = getUrlParameter('category_id') || 'all';
        loadProducts(categoryId, sortBy);
        updateUrl('sort_by', sortBy);
    });

    $('#showTreeBtn').click(function() {
        $.getJSON('/?action=getCategoryTree', function(data) {
            var treeWindow = window.open("", "Category Tree", "width=800,height=600");
            treeWindow.document.write("<html><head><title>Category Tree</title>");
            treeWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
            treeWindow.document.write('<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jsonview/1.2.3/jquery.jsonview.min.css">');
            treeWindow.document.write("</head><body>");
            treeWindow.document.write("<div class='container'><h2>Category Tree</h2><div id='json-viewer'></div>");
            treeWindow.document.write("<p>Execution Time: " + data.execution_time + " seconds</p>");
            treeWindow.document.write("<button onclick=\"window.location.href='http://localhost:8080/'\" class='btn btn-primary'>Back to Main Page</button>");
            treeWindow.document.write("</div></body></html>");
            treeWindow.document.close();

            $(treeWindow.document).ready(function() {
                $(treeWindow.document).find("#json-viewer").JSONView(data.tree);
            });
        });
    });

    function loadCategories() {
        $.getJSON('/?action=getCategoryCounts', function(data) {
            $('#categories').empty();
            var totalProducts = data.reduce((acc, cat) => acc + parseInt(cat.product_count || 0, 10), 0);
            $('#categories').append('<li class="list-group-item d-flex justify-content-between align-items-center">' +
                '<a href="#" class="category-link" data-id="all">All Categories</a>' +
                '<span class="badge badge-primary badge-pill">' + totalProducts + '</span>' +
                '</li>');
            $.each(data, function(index, category) {
                $('#categories').append('<li class="list-group-item d-flex justify-content-between align-items-center">' +
                    '<a href="#" class="category-link" data-id="' + category.id + '">' + category.name + '</a>' +
                    '<span class="badge badge-primary badge-pill">' + category.product_count + '</span>' +
                    '</li>');
            });
        });
    }

    function loadProducts(categoryId = 'all', sortBy = 'created_at DESC') {
        var url = '/?action=getProducts';
        if (categoryId !== 'all') {
            url = '/?action=getProductsByCategory&category_id=' + categoryId;
        }
        if (sortBy) {
            url += '&sort_by=' + sortBy;
        }

        $.getJSON(url, function(data) {
            $('#products').empty();
            $.each(data, function(index, product) {
                var productCard = '<div class="col-md-4 mb-4">' +
                    '<div class="card">' +
                    '<div class="card-body">' +
                    '<h5 class="card-title">' + product.name + '</h5>' +
                    '<p class="card-text">$' + product.price + '</p>' +
                    '<button class="btn btn-primary buy-button" data-id="' + product.id + '">Купити</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                $('#products').append(productCard);
            });

            $('.buy-button').click(function() {
                var productId = $(this).data('id');
                showModal(productId);
            });
        });
    }

    function updateUrl(key, value) {
        var currentUrl = window.location.href;
        var newUrl = new URL(currentUrl);
        newUrl.searchParams.set(key, value);
        history.pushState(null, '', newUrl);
    }

    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }

    function showModal(productId) {
        $.getJSON('/?action=getProduct&id=' + productId, function(data) {
            var modalContent = '<div>' + data.name + ' - $' + data.price + '</div>';
            $('#productModal .modal-body').html(modalContent);
            $('#productModal').modal('show');
        });
    }
});
