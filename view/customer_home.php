
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: 404.php");
    exit();
}

$first_name = $_SESSION['first_name'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <span>Welcome, <?php echo $first_name; ?> (<?php echo ucfirst($role); ?>)</span>
       
        
    </nav>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        header {
            display: flex;
            align-items: center;
            background-color: #07c43d;
            color: #fff;
            padding: 10px 20px;
        }

        .toggle-button {
            background-color: #07c43d;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .header-content {
            margin-left: 10px;
        }

        .header-content h1 {
            margin: 0;
        }

        .nav-links {
            display: flex;
            justify-content: center;
            padding: 10px;
            background-color: #07c43d;

        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            margin: 0 15px;
            font-size: 18px;
        }

        .nav-links a:hover {
            color: #007BFF;
        }

        .logo {
            margin-left: auto;
        }

        .logo img {
            max-height: 40px;
        }

        .toggle-menu {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }

        .toggle-menu ul {
            list-style: none;
            padding: 0;
        }

        .toggle-menu li {
            margin: 20px 0;
        }

        .toggle-menu a {
            text-decoration: none;
            color: #fff;
        }

        .toggle-menu a:hover {
            color: #007BFF;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
</head>
<body>
    <header>
        <button class="toggle-button" onclick="toggleMenu()">☰</button>
        <div class="header-content">
            <h1>CTS Courier PMS</h1>
        </div>
        <div class="logo">
            <img src="logo.png" alt="Logo">
        </div>
    </header>
    <div class="nav-links">
        <a href="custom_home.php">Home</a>
        <a href="../model/user_model/track_your_parcel.html">Track Your Parcel</a>
        <a href="../model/user_model/contact_page.html">Contact</a>
        <a href="../authentication/logout.php">Logout</a>
    </div>

    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

    <div class="toggle-menu" id="toggleMenu">
        <ul>
            <li><a href="#">Profile</a></li>
        </ul>
    </div>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('toggleMenu');
            const overlay = document.getElementById('overlay');
            const isMenuOpen = menu.style.display === 'block';

            menu.style.display = isMenuOpen ? 'none' : 'block';
            overlay.style.display = isMenuOpen ? 'none' : 'block';
        }
    </script>
</body>
</html>
