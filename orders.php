<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to view your orders.";
    header("Location: index.php");
    exit();
}

try {
    // Get all orders for the current user
    $stmt = $pdo->prepare("
        SELECT o.*, 
               COUNT(oi.id) as total_items,
               GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE o.user_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $orders = $stmt->fetchAll();

} catch(PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}
?>

<div class="container mt-5">
    <h2 class="mb-4">My Orders</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="alert alert-info">
            You haven't placed any orders yet. 
            <a href="products.php" class="alert-link">Start shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach($orders as $order): ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Order #<?php echo $order['id']; ?></h5>
                            <span class="badge bg-<?php 
                                echo match($order['status']) {
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Order Date:</strong> 
                                <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?>
                            </div>
                            <div class="mb-3">
                                <strong>Total Amount:</strong> 
                                ₱<?php echo number_format($order['total_amount'], 2); ?>
                            </div>
                            <div class="mb-3">
                                <strong>Items:</strong> 
                                <span class="text-muted"><?php echo $order['total_items']; ?> items</span>
                                <div class="small text-muted mt-1">
                                    <?php echo htmlspecialchars($order['product_names']); ?>
                                </div>
                            </div>
                            <!-- <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">
                                View Details
                            </a> -->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?> 