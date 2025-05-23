<?php
require_once '../includes/admin_header.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: products.php");
    exit();
}

// Get form data
$product_id = $_POST['id'];
$name = $_POST['name'];
$category_id = $_POST['category_id'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$description = $_POST['description'];

try {
    // Start transaction
    $pdo->beginTransaction();

    // Handle image upload if a new image was provided
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/products/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Generate unique filename
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $filename = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $filename;

        // Check if file is an actual image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            throw new Exception("File is not an image.");
        }

        // Check file size (5MB max)
        if ($_FILES['image']['size'] > 5000000) {
            throw new Exception("File is too large. Maximum size is 5MB.");
        }

        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Upload file
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_url = 'uploads/products/' . $filename;
        } else {
            throw new Exception("Failed to upload image.");
        }
    }

    // Update product in database
    $sql = "UPDATE products SET 
            name = ?, 
            category_id = ?, 
            price = ?, 
            stock = ?, 
            description = ?";
    
    $params = [$name, $category_id, $price, $stock, $description];

    // Add image_url to update if new image was uploaded
    if ($image_url) {
        $sql .= ", image_url = ?";
        $params[] = $image_url;
    }

    $sql .= " WHERE id = ?";
    $params[] = $product_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Commit transaction
    $pdo->commit();

    // Redirect back to products page with success message
    $_SESSION['success'] = "Product updated successfully!";
    header("Location: products.php");
    exit();

} catch (Exception $e) {
    // Rollback transaction on error
    $pdo->rollBack();
    
    $_SESSION['error'] = "Error updating product: " . $e->getMessage();
    header("Location: edit_product.php?id=" . $product_id);
    exit();
} 