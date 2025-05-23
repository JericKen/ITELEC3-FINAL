<?php
require_once '../includes/admin_header.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

try {
    // Get all orders with user and item details
    $stmt = $pdo->prepare("
        SELECT o.*, 
               u.name as customer_name,
               u.email as customer_email,
               COUNT(oi.id) as total_items,
               GROUP_CONCAT(p.name SEPARATOR ', ') as product_names
        FROM orders o
        JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        GROUP BY o.id
        ORDER BY o.created_at DESC
    ");
    $stmt->execute();
    $orders = $stmt->fetchAll();

} catch(PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
}
?>

<div class="container mt-4">
    <h2>Order Management</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Order Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text display-4"><?php echo count($orders); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <p class="card-text display-4">
                        <?php 
                        echo count(array_filter($orders, function($order) {
                            return $order['status'] === 'pending';
                        }));
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Delivered Orders</h5>
                    <p class="card-text display-4">
                        <?php 
                        echo count(array_filter($orders, function($order) {
                            return $order['status'] === 'delivered';
                        }));
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Cancelled Orders</h5>
                    <p class="card-text display-4">
                        <?php 
                        echo count(array_filter($orders, function($order) {
                            return $order['status'] === 'cancelled';
                        }));
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card mt-4 mb-4">
        <div class="card-header">
            <h5 class="mb-0">All Orders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($order['customer_name']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $order['total_items']; ?> items</span>
                                    <div class="small text-muted">
                                        <?php echo htmlspecialchars($order['product_names']); ?>
                                    </div>
                                </td>
                                <td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
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
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <!-- <button type="button" 
                                                class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                data-bs-toggle="dropdown">
                                            <i class="bi bi-arrow-down-circle"></i> Status
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'pending')">
                                                    <i class="bi bi-clock"></i> Pending
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'processing')">
                                                    <i class="bi bi-gear"></i> Processing
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'shipped')">
                                                    <i class="bi bi-truck"></i> Shipped
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'delivered')">
                                                    <i class="bi bi-check-circle"></i> Delivered
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" 
                                                   onclick="updateOrderStatus(<?php echo $order['id']; ?>, 'cancelled')">
                                                    <i class="bi bi-x-circle"></i> Cancelled
                                                </a>
                                            </li>
                                        </ul> -->
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Are you sure you want to update this order\'s status to ' + status + '?')) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${orderId}&status=${status}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Failed to update order status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the order status');
        });
    }
}
</script>

<?php require_once '../includes/admin_footer.php'; ?> 