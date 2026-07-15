**create_منتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);
    $quantity = trim($_POST['quantity']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($price) || empty($quantity)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $price, $quantity);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_منتجات.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create New Product</h2>
    <form id="create-product-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-lg focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">Create Product</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/منتجات.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_منتجات.php';
                    } else {
                        alert('Error creating product');
                    }
                }
            });
        });
    });
</script>


**Note:** This code assumes you have the following files:

* `db.php`: a database connection file
* `header.php`: a header file that includes the HTML header
* `footer.php`: a footer file that includes the HTML footer
* `list_منتجات.php`: a list page for products
* `backend/منتجات.php`: a backend file that handles the product creation

Also, this code uses jQuery for the AJAX request. Make sure to include the jQuery library in your HTML file.