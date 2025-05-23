<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTS Courier Login</title>
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
        .login-container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
        }
        .login-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        .login-header p {
            color: #666;
            font-size: 14px;
        }
        .login-form {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
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
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .register-text {
            margin-top: 10px;
            font-size: 14px;
        }
        .register-text a {
            color: #007bff;
            text-decoration: none;
        }
        .register-text a:hover {
            text-decoration: underline;
        }
        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f4f4f9;
            height: 100vh;
            padding: 20px;
        }
        .card-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .image-card {
            background-color: #ffffff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        .image-card img {
            width: 440px;
            max-width: 655px;
            height: auto;
            border-radius: 10px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="card-wrapper">
            <div class="image-card">
                <img src="../includes/img/logo.png" alt="Courier Image">
            </div>
            <div class="login-container">
                <div class="login-header">
                    <h1>CTS Courier Services</h1>
                    <p>Login Form</p>
                </div>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert-danger"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                <form id="loginForm" class="login-form" method="POST" action="login_p.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="username" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn">Login</button>
                    </div>
                    <p class="register-text">Don't have an account? <a href="register.php">Register</a></p>
                    <p class="register-text">Forgot Password? <a href="forgot_password.php">click here</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>