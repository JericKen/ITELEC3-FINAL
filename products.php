<?php
require_once 'includes/header.php';

$category_id = isset($_GET['category']) ? $_GET['category'] : null;
$products = [];
$categories = [];

try {
    // Get all categories for filter sidebar or dropdown
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    $categories = $stmt->fetchAll();

    // Get products (optionally filter by category)
    if ($category_id) {
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id WHERE c.id = ? ORDER BY p.created_at DESC");
        $stmt->execute([$category_id]);
    } else {
        $stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC");
    }
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <a href="products.php" class="list-group-item<?php if(!$category_id) echo ' active'; ?>">All</a>
                    <?php foreach($categories as $cat): ?>
                        <a href="products.php?category=<?php echo $cat['id']; ?>" class="list-group-item<?php if($category_id == $cat['id']) echo ' active'; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </a>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-md-9">
            <h2 class="mb-4">
                <?php
                if ($category_id) {
                    foreach($categories as $cat) {
                        if ($cat['id'] == $category_id) {
                            echo htmlspecialchars($cat['name']);
                            break;
                        }
                    }
                } else {
                    echo 'All Products';
                }
                ?>
            </h2>
            <div class="row">
                <?php if (count($products) > 0): ?>
                    <?php foreach($products as $product): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 350px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <div class="mb-2 text-muted"><?php echo htmlspecialchars($product['category_name']); ?></div>
                                    <div class="fw-bold mb-2">₱<?php echo number_format($product['price'], 2); ?></div>
                                    <div class="d-flex gap-2 mt-auto">
                                        <!-- <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary flex-grow-1">View Details</a> -->
                                        <a href="purchase.php?product_id=<?php echo $product['id']; ?>" class="btn btn-primary flex-grow-1">Buy Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">No products found in this category.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?> 