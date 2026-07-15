<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = isset($input['limit']) ? (int) $input['limit'] : 10;
    $offset = isset($input['offset']) ? (int) $input['offset'] : 0;

    // Check user role
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('
        SELECT *
        FROM تسليم
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ');
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch and output data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Check user role
    if ($userRole !== 'admin' && $userRole !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('
        INSERT INTO تسليم (name, description)
        VALUES (:name, :description)
    ');
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Output ID of newly created record
    $id = $pdo->lastInsertId();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $id]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $id = isset($input['id']) ? (int) $input['id'] : 0;
    $name = isset($input['name']) ? trim($input['name']) : '';
    $description = isset($input['description']) ? trim($input['description']) : '';

    // Check user role
    if ($userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('
        UPDATE تسليم
        SET name = :name, description = :description
        WHERE id = :id
    ');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();

    // Output success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = isset($input['id']) ? (int) $input['id'] : 0;

    // Check user role
    if ($userRole !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('
        DELETE FROM تسليم
        WHERE id = :id
    ');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Output success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Record deleted successfully']);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}