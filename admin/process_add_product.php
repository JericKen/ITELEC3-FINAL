<?php
require_once '../includes/admin_header.php';
require_once '../config/database.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $name = trim($_POST['name']);
        $category_id = $_POST['category_id'];
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $description = trim($_POST['description']);

        // Validate required fields
        if (empty($name) || empty($category_id) || empty($price) || empty($stock) || empty($description)) {
            $_SESSION['error'] = "All fields are required.";
            header("Location: add_product.php");
            exit();
        }

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($_FILES['image']['type'], $allowed_types)) {
                $_SESSION['error'] = "Invalid image format. Please upload JPG, PNG, or GIF.";
                header("Location: add_product.php");
                exit();
            }

            if ($_FILES['image']['size'] > $max_size) {
                $_SESSION['error'] = "Image size too large. Maximum size is 5MB.";
                header("Location: add_product.php");
                exit();
            }

            // Generate unique filename
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $file_extension;
            $upload_path = '../uploads/products/' . $filename;

            // Create directory if it doesn't exist
            if (!file_exists('../uploads/products')) {
                mkdir('../uploads/products', 0777, true);
            }

            // Move uploaded file
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: add_product.php");
                exit();
            }

            // Store the relative path in the database
            $image_path = 'uploads/products/' . $filename;
        } else {
            $_SESSION['error'] = "Please upload a product image.";
            header("Location: add_product.php");
            exit();
        }

        // Insert product into database
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, price, stock, description, image_url, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$name, $category_id, $price, $stock, $description, $image_path]);

        // Redirect to products page with success message
        $_SESSION['success'] = "Product added successfully!";
        header("Location: products.php");
        exit();

    } catch(PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: add_product.php");
        exit();
    }
} else {
    // If not POST request, redirect to add product page
    header("Location: add_product.php");
    exit();
}
?> 