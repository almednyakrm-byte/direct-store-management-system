<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Validate and sanitize input
    if (!isset($_GET['limit']) || !is_numeric($_GET['limit'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid limit']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM products LIMIT :limit');
    $stmt->bindParam(':limit', $_GET['limit']);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle GET request by ID
if (isset($_GET['action']) && $_GET['action'] == 'get_by_id') {
    // Validate and sanitize input
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Product not found']);
    }
    exit;
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    // Validate and sanitize input
    if (!isset($inputData['name']) || !isset($inputData['price']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO products (name, price, description) VALUES (:name, :price, :description)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':price', $inputData['price']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();

    // Return created product ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
    exit;
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    // Validate and sanitize input
    if (!isset($inputData['id']) || !is_numeric($inputData['id']) || !isset($inputData['name']) || !isset($inputData['price']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE products SET name = :name, price = :price, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':price', $inputData['price']);
    $stmt->bindParam(':description', $inputData['description']);
    $stmt->execute();

    // Return updated product ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $inputData['id']]);
    exit;
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    // Validate and sanitize input
    if (!isset($inputData['id']) || !is_numeric($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid ID']);
        exit;
    }

    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->execute();

    // Return deleted product ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $inputData['id']]);
    exit;
}

// Return error for unknown action
http_response_code(400);
echo json_encode(['error' => 'Unknown action']);
exit;