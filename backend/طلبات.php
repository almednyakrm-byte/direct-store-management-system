<?php

require_once 'db.php';

// Get user role and check if user is logged in
if (!isset($_SESSION['role']) || !isset($_SESSION['logged_in'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all orders
    $stmt = $pdo->prepare('SELECT * FROM orders');
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return orders
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($orders);
}

// Handle POST request
elseif ($method === 'POST') {
    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate data
    if (!isset($data['customer_name']) || !isset($data['order_date']) || !isset($data['total'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize data
    $customer_name = filter_var($data['customer_name'], FILTER_SANITIZE_STRING);
    $order_date = filter_var($data['order_date'], FILTER_SANITIZE_STRING);
    $total = filter_var($data['total'], FILTER_SANITIZE_NUMBER_INT);

    // Insert order
    $stmt = $pdo->prepare('INSERT INTO orders (customer_name, order_date, total) VALUES (:customer_name, :order_date, :total)');
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':order_date', $order_date);
    $stmt->bindParam(':total', $total);
    $stmt->execute();

    // Return order ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('order_id' => $pdo->lastInsertId()));
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate data
    if (!isset($data['order_id']) || !isset($data['customer_name']) || !isset($data['order_date']) || !isset($data['total'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize data
    $order_id = filter_var($data['order_id'], FILTER_SANITIZE_NUMBER_INT);
    $customer_name = filter_var($data['customer_name'], FILTER_SANITIZE_STRING);
    $order_date = filter_var($data['order_date'], FILTER_SANITIZE_STRING);
    $total = filter_var($data['total'], FILTER_SANITIZE_NUMBER_INT);

    // Update order
    $stmt = $pdo->prepare('UPDATE orders SET customer_name = :customer_name, order_date = :order_date, total = :total WHERE id = :order_id');
    $stmt->bindParam(':order_id', $order_id);
    $stmt->bindParam(':customer_name', $customer_name);
    $stmt->bindParam(':order_date', $order_date);
    $stmt->bindParam(':total', $total);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Order updated successfully'));
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get order ID
    $order_id = filter_var($_GET['order_id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete order
    $stmt = $pdo->prepare('DELETE FROM orders WHERE id = :order_id');
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Order deleted successfully'));
}

// Return error message for invalid request method
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}