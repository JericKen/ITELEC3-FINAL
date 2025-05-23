<?php
require_once '../includes/admin_header.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: orders.php");
    exit();
}

// Get and validate input
$order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

// Validate status
$allowed_statuses = ['processing', 'delivered', 'cancelled'];
if (!in_array($status, $allowed_statuses)) {
    $_SESSION['error'] = "Invalid status selected.";
    header("Location: order-details.php?id=" . $order_id);
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Check if order exists
    $stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    if (!$order) {
        throw new Exception("Order not found.");
    }

    // Update order status
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $order_id]);

    // If order is cancelled, restore product stock
    if ($status === 'cancelled') {
        // Get order items
        $stmt = $pdo->prepare("
            SELECT oi.product_id, oi.quantity 
            FROM order_items oi 
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$order_id]);
        $items = $stmt->fetchAll();

        // Restore stock for each item
        foreach ($items as $item) {
            $stmt = $pdo->prepare("
                UPDATE products 
                SET stock = stock + ? 
                WHERE id = ?
            ");
            $stmt->execute([$item['quantity'], $item['product_id']]);
        }
    }

    // Commit transaction
    $pdo->commit();

    $_SESSION['success'] = "Order status updated successfully.";
} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    $_SESSION['error'] = "Error updating order status: " . $e->getMessage();
}

header("Location: order-details.php?id=" . $order_id);
exit(); 