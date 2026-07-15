**list_طلبات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات</title>
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
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <p class="mr-2">مرحباً, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" class="text-red-600 hover:text-red-800">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="max-w-7xl mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">طلبات</h1>
        <div class="flex justify-between items-center mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_طلبات.php'">إضافة جديد</button>
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600" placeholder="بحث" id="search" oninput="filterList()">
        </div>
        <table class="w-full table-auto border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="px-4 py-2">الاسم</th>
                    <th class="px-4 py-2">العنوان</th>
                    <th class="px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="list">
                <?php
                // Fetch list records from backend
                $url = '../backend/طلبات.php';
                $response = fetch($url);
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    ?>
                    <tr>
                        <td class="px-4 py-2"><?= $item['name'] ?></td>
                        <td class="px-4 py-2"><?= $item['address'] ?></td>
                        <td class="px-4 py-2 flex justify-between items-center">
                            <a href="edit_طلبات.php?id=<?= $item['id'] ?>" class="text-teal-500 hover:text-teal-800">تعديل</a>
                            <button class="text-red-600 hover:text-red-800" onclick="deleteItem(<?= $item['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        function filterList() {
            const search = document.getElementById('search').value.toLowerCase();
            const list = document.getElementById('list');
            const rows = list.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(search)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                fetch('../backend/طلبات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function fetch(url) {
            return fetch(url)
            .then(response => response.json())
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`../backend/طلبات.php`) that handles GET and DELETE requests for fetching and deleting records. The `fetch` function in the JavaScript code is used to make AJAX requests to the backend script.