<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .logo span {
            color: #008000;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-group input[type="email"] {
            background-color: #f0f0f0;
        }
        .form-group input[type="password"] {
            background-color: #f0f0f0;
        }
        .btn {
            width: 100%;
            height: 40px;
            padding: 10px;
            font-size: 16px;
            background-color: #008000;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #005500;
        }
        .error {
            color: #ff0000;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span>Register</span>
        </div>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
            </div>
            <button class="btn" type="submit">Register</button>
            <div class="error" id="error-message"></div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'POST',
                    url: '../backend/auth.php?action=register',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            alert('Registration successful. Please login to continue.');
                            window.location.href = 'login.php';
                        } else {
                            $('#error-message').text(response);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking frontend page for user registration. It includes a form with fields for username, email, and password, and uses AJAX to submit the form data to the backend for processing. The form fields are validated using HTML5 validation attributes, and the error messages are displayed below the form fields. The code also includes a button to submit the form, and a link to the login page after successful registration.