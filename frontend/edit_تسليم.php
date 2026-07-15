**edit_تسليم.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/تسليم.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    // Populate form fields
    $name = $data['name'];
    $description = $data['description'];
    $status = $data['status'];
} else {
    // Handle error
    echo 'Error fetching data';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit تسليم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit تسليم</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-bold text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-lg" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-bold text-gray-700">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-lg"><?= $description ?></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-bold text-gray-700">Status:</label>
                <select id="status" name="status" class="w-full p-2 text-sm text-gray-700 border border-gray-300 rounded-lg">
                    <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
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
                    url: '../backend/تسليم.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_تسليم.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>

**Note:** Make sure to replace `../backend/تسليم.php` with the actual URL of your backend API. Also, this code assumes that the backend API returns a JSON response with the updated record details.