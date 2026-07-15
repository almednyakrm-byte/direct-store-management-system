**edit_منتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = "
<script>
    fetch('../backend/منتجات.php?id=$id')
        .then(response => response.json())
        .then(data => {
            document.getElementById('name').value = data.name;
            document.getElementById('price').value = data.price;
            document.getElementById('description').value = data.description;
        });
</script>
";

// Display product details
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit Product</h1>
        <form id="edit-product-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
                <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update Product</button>
        </form>
    </div>

    <?php echo $js; ?>

    <script>
        document.getElementById('edit-product-form').addEventListener('submit', function(event) {
            event.preventDefault();
            fetch('../backend/منتجات.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: $id,
                    name: document.getElementById('name').value,
                    price: document.getElementById('price').value,
                    description: document.getElementById('description').value
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_منتجات.php';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>


**backend/منتجات.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Product ID not set'));
    exit;
}

// Get product ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch product details
$product = $result->fetch_assoc();

// Close connection
$conn->close();

// Output product details
echo json_encode(array(
    'success' => true,
    'name' => $product['name'],
    'price' => $product['price'],
    'description' => $product['description']
));


**backend/update_product.php**

<?php
// Check if product ID is set
if (!isset($_POST['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Product ID not set'));
    exit;
}

// Get product ID
$id = $_POST['id'];

// Get product details
$name = $_POST['name'];
$price = $_POST['price'];
$description = $_POST['description'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update product details
$stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
$stmt->bind_param("sssi", $name, $price, $description, $id);
$stmt->execute();

// Check if update was successful
if ($stmt->affected_rows == 1) {
    echo json_encode(array('success' => true, 'message' => 'Product updated successfully'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Failed to update product'));
}

// Close connection
$conn->close();