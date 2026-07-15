<?php
session_start();

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
    <title>نظام إدارة متاجر بيع مباشر</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-white border-b border-gray-200">
        <h1 class="text-lg font-bold text-emerald-600">نظام إدارة متاجر بيع مباشر</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل خروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-white border-b border-gray-200">
        <h1 class="text-2xl font-bold text-emerald-600">مرحباً <?= $_SESSION['username'] ?></h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
        <div class="glassmorphism-card bg-white p-4">
            <h2 class="text-lg font-bold text-emerald-600">إحصائيات</h2>
            <div id="stats"></div>
        </div>
        <div class="glassmorphism-card bg-white p-4">
            <h2 class="text-lg font-bold text-emerald-600">إدارة المنتجات</h2>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='products.php'">إدارة المنتجات</button>
        </div>
        <div class="glassmorphism-card bg-white p-4">
            <h2 class="text-lg font-bold text-emerald-600">إدارة الطلبات</h2>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='orders.php'">إدارة الطلبات</button>
        </div>
        <div class="glassmorphism-card bg-white p-4">
            <h2 class="text-lg font-bold text-emerald-600">إدارة التسليم</h2>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='deliveries.php'">إدارة التسليم</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script>
        axios.get('api/stats.php')
            .then(response => {
                const stats = document.getElementById('stats');
                stats.innerHTML = `
                    <p>عدد المنتجات: ${response.data.products}</p>
                    <p>عدد الطلبات: ${response.data.orders}</p>
                    <p>عدد التسليمات: ${response.data.deliveries}</p>
                `;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes that you have a PHP file named `api/stats.php` that returns a JSON response with the stats data. You'll need to create this file and implement the API endpoint to fetch the stats data.


<?php
header('Content-Type: application/json');

// Fetch stats data from database or other source
$stats = array(
    'products' => 100,
    'orders' => 50,
    'deliveries' => 20
);

echo json_encode($stats);
?>


This is a basic example, and you'll need to modify it to fit your specific requirements.