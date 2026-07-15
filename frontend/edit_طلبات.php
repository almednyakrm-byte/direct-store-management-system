**edit_طلبات.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/طلبات.php?id=' . $id;
$response = json_decode(file_get_contents($url), true);

// Check if record exists
if (empty($response)) {
    echo 'Record not found!';
    exit;
}

// Assign record details to variables
$record = $response;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit Record</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600" value="<?= $record['title'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 bg-gray-50 rounded-lg border border-gray-300 focus:ring-emerald-600 focus:border-emerald-600"><?= $record['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update Record</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/طلبات.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error updating record!');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/طلبات.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    http_response_code(400);
    echo 'Invalid request!';
    exit;
}

// Get record ID
$id = $_GET['id'];

// Check if record exists
$record = get_record($id);

if (empty($record)) {
    http_response_code(404);
    echo 'Record not found!';
    exit;
}

// Update record
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    update_record($id, $data);
    echo json_encode(['success' => true]);
    exit;
}

// Helper functions
function get_record($id) {
    // Implement database query to get record by ID
}

function update_record($id, $data) {
    // Implement database query to update record
}
?>