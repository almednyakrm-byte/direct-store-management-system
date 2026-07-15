**create_تسليم.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12">
    <h1 class="text-3xl font-bold mb-4">Create تسليم</h1>

    <form id="create-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required></textarea>
        </div>

        <div class="mb-4">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date</label>
            <input type="date" id="date" name="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>

        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create تسليم</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: '../backend/تسليم.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_تسليم.php';
                    } else {
                        alert('Error creating تسليم');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/تسليم.php**

<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to database
    $conn = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');

    // Prepare and execute query
    $stmt = $conn->prepare('INSERT INTO تسليم (name, description, date) VALUES (:name, :description, :date)');
    $stmt->bindParam(':name', $_POST['name']);
    $stmt->bindParam(':description', $_POST['description']);
    $stmt->bindParam(':date', $_POST['date']);
    $stmt->execute();

    // Close database connection
    $conn = null;

    // Output success response
    echo json_encode(['success' => true]);
} else {
    // Output error response
    echo json_encode(['success' => false]);
}
?>


Note: Replace `database_name`, `username`, and `password` with your actual database credentials. Also, make sure to adjust the `list_تسليم.php` URL to match your actual file path.