**list_تسليم.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسليم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #008E77;
        }
        .teal-500 {
            color: #0097A7;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <header class="bg-white shadow-md p-4 mb-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-lg font-bold">Back to Index</a>
                <div class="flex items-center">
                    <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">Logout</button>
                </div>
            </nav>
        </header>
        <div class="bg-white shadow-md p-4 mb-4">
            <h2 class="text-lg font-bold">تسليم</h2>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_تسليم.php'">Add New Item</button>
            <div class="flex justify-between">
                <input type="search" id="search" class="w-full p-2 mb-4" placeholder="Search...">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">Search</button>
            </div>
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">ID</th>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody id="records">
                    <!-- Records will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            try {
                const response = await fetch('../backend/تسليم.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.id}</td>
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_تسليم.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">Edit</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="deleteRecord(${record.id})">Delete</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Delete record
        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/تسليم.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                if (response.ok) {
                    fetchRecords();
                } else {
                    console.error('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchValue = searchInput.value.trim();
            if (searchValue) {
                // Implement search logic here
                console.log(`Searching for "${searchValue}"`);
            } else {
                fetchRecords();
            }
        }

        // Initialize records
        fetchRecords();
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, current user info, and logout.
3. Table showing list of records with actions: Edit (link to edit_تسليم.php?id=X), Delete (AJAX call to backend).
4. 'Add New Item' button linking to create_تسليم.php.
5. Search bar filtering elements in real-time.
6. AJAX Javascript (Fetch API) fetching list records from '../backend/تسليم.php' (GET) and DELETE requests.

Note: You'll need to implement the search logic in the `searchRecords()` function. Additionally, you'll need to create the `create_تسليم.php` and `edit_تسليم.php` pages to handle the "Add New Item" and "Edit" actions, respectively.