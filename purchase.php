<?php

// Start session and include required files at the very beginning
session_start();
require_once 'config/database.php';

// Check if user is logged in before any output
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login to make a purchase.";
    header("Location: login.php");
    exit();
}

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Get product details
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();

        if (!$product) {
            throw new Exception("Product not found.");
        }

        // Calculate total price
        $total_price = $product['price'] * $quantity;

        // Create order
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$_SESSION['user_id'], $total_price]);
        $order_id = $pdo->lastInsertId();

        // Create order items
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->execute([$order_id, $product_id, $quantity, $product['price']]);

        // Update product stock
        $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->execute([$quantity, $product_id]);

        // Commit transaction
        $pdo->commit();

        $_SESSION['success'] = "Order placed successfully!";
        header("Location: order-confirmation.php?id=" . $order_id);
        exit();

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        $_SESSION['error'] = "Error processing order: " . $e->getMessage();
    }
}

// Get product details for display
try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        $_SESSION['error'] = "Product not found.";
        header("Location: products.php");
        exit();
    }
} catch(PDOException $e) {
    $_SESSION['error'] = "Error: " . $e->getMessage();
    header("Location: products.php");
    exit();
}

// Include header after all potential redirects
require_once 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 mb-4">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 350px; width: 100%; height: auto;">
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
            <h4 class="text-primary">₱<?php echo number_format($product['price'], 2); ?></h4>
            
            <form method="POST" action="purchase.php?product_id=<?php echo $product_id; ?>" class="mt-4">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" required>
                    <small class="text-muted">Available stock: <?php echo $product['stock']; ?></small>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">Buy Now</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 