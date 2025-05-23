<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'includes/header.php';
require_once 'config/database.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Get order details
    $stmt = $pdo->prepare("
        SELECT o.*, oi.quantity, oi.price as item_price, p.name as product_name, p.image_url
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$order_id, $_SESSION['user_id']]);
    $order_items = $stmt->fetchAll();

    if (empty($order_items)) {
        $_SESSION['error'] = "Order not found.";
        header("Location: products.php");
        exit();
    }

    // Get order summary
    $order = [
        'id' => $order_items[0]['id'],
        'total_amount' => $order_items[0]['total_amount'],
        'status' => $order_items[0]['status'],
        'created_at' => $order_items[0]['created_at']
    ];

} catch(PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: products.php");
    exit();
}
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Order Confirmation</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5 class="alert-heading">Thank you for your order!</h5>
                        <p>Your order has been successfully placed.</p>
                    </div>

                    <div class="mb-4">
                        <h5>Order Details</h5>
                        <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
                        <p><strong>Order Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></p>
                        <p><strong>Status:</strong> <span class="badge bg-primary"><?php echo ucfirst($order['status']); ?></span></p>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo htmlspecialchars($item['image_url']); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                            <?php echo htmlspecialchars($item['product_name']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>₱<?php echo number_format($item['item_price'], 2); ?></td>
                                    <td>₱<?php echo number_format($item['quantity'] * $item['item_price'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>₱<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="products.php" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 