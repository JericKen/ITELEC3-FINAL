<?php
require_once 'includes/header.php';

try {
    // Get featured products
    $stmt = $pdo->query("SELECT p.*, c.name as category_name 
                        FROM products p 
                        JOIN categories c ON p.category_id = c.id 
                        ORDER BY p.created_at DESC 
                        LIMIT 8");
    $featured_products = $stmt->fetchAll();

    // Get categories
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!-- Featured Products Section-->
<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php foreach($featured_products as $product): ?>
                <div class="col mb-5">
                    <div class="card h-100">
                        <!-- Product image-->
                        <div class="product-image-container" style="height: 350px; overflow: hidden;">
                            <img class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;" />
                        </div>
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <!-- Product category-->
                                <div class="text-muted mb-2"><?php echo htmlspecialchars($product['category_name']); ?></div>
                                <!-- Product price-->
                                <div class="fw-bold">₱<?php echo number_format($product['price'], 2); ?></div>
                            </div>
                        </div>
                        <!-- Product actions-->
                        <!-- <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center">
                                <a class="btn btn-outline-dark mt-auto" href="product.php?id=<?php echo $product['id']; ?>">View Details</a>
                            </div>
                        </div> -->
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Categories Section-->
<section class="py-5 bg-light">
    <div class="container px-4 px-lg-5">
        <h2 class="text-center mb-4">Browse by Category</h2>
        <div class="row gx-4 gx-lg-5 justify-content-start">
            <?php foreach($categories as $category): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($category['description']); ?></p>
                            <a href="products.php?category=<?php echo $category['id']; ?>" class="btn btn-primary">View Products</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 