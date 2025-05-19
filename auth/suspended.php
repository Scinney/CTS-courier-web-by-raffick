<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Suspended - CTS Courier</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .suspended-container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .suspended-header h1 {
            font-size: 24px;
            color: #dc3545; /* Red to indicate error */
            margin-bottom: 10px;
        }
        .suspended-header p {
            color: #333;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .contact-info {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
        .contact-info a {
            color: #007bff;
            text-decoration: none;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="suspended-container">
        <div class="suspended-header">
            <h1>Account Suspended</h1>
            <p>Your account has been suspended. Please contact an administrator to resolve this issue.</p>
        </div>
        <a href="login.php" class="btn">Back to Login</a>
        <div class="contact-info">
            <p>Contact Admin: <a href="mailto:admin@ctscourier.com">admin@ctscourier.com</a></p>
            <p>Phone: +123-456-7890</p>
        </div>
    </div>
</body>
</html>