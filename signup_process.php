<?php
session_start();
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Log the received data (remove in production)
error_log("Signup attempt - Name: $name, Email: $email");

if (!$name || !$email || !$password || !$confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all fields']);
    exit();
}

if ($password !== $confirm_password) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
    exit();
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long']);
    exit();
}

try {
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Email already registered']);
        exit();
    }

    // Create new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
    $result = $stmt->execute([$name, $email, $hashed_password]);

    if (!$result) {
        error_log("Failed to insert user: " . print_r($stmt->errorInfo(), true));
        throw new Exception("Failed to create user account");
    }

    // Get the new user's ID
    $user_id = $pdo->lastInsertId();

    // Set session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $name;

    echo json_encode(['success' => true]);
} catch(PDOException $e) {
    error_log("Database error during signup: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred during signup. Please try again.']);
} catch(Exception $e) {
    error_log("General error during signup: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 