<?php
session_start();
$username = $_SESSION['username'] ?? 'User'; 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us - CODEVERSE</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color:  #1E1E3F;
            color: #111827;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 5%;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .logo {
            font-weight: bold;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo img {
            width: 30px;
            height: 30px;
        }

        nav {
            display: flex;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: #1E1E3F;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav a:hover {
            background-color: #E5E7EB;
            color: #1E1E3F;
        }

        nav a.active {
            background-color: #1E1E3F;
            color: white;
        }

        .container {
            padding: 40px 5%;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
            color: white;
        }

        .team {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        .member {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            width: 250px;
        }

        .member img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 16px;
        }

        .member h3 {
            font-size: 18px;
            margin-bottom: 6px;
        }

        .member p {
            font-size: 14px;
            color: #555;
        }
        .userMenu {
            position: relative;
            cursor: pointer;
            font-weight: bold;
        }
        
        .dropdown {
            display: none;
            position: absolute;
            top: 130%;
            right: 0;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 8px 16px;
            font-size: 14px;
            z-index: 999;
        }

    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="logo_.png" alt="Logo">
            <span>CODEVERSE</span>
        </div>

        <nav>
           <a href="home.php">Home</a>
            <a href="lists.php" >Members</a>
            <a href="addMember.php">Add</a>
            <a href="aboutUs.php" class="active">About Us</a>
        </nav>

        <div class="userMenu" onclick="toggleDropdown()">
            <?= htmlspecialchars($username) ?>
            <div class="dropdown" id="logoutDropdown">
                <form action="logout.php" method="post">
                    <button type="submit"
                        style="background: none; border: none; color: red; cursor: pointer;">Logout</button>
                </form>
            </div>
        </div>
    </header>


    <div class="container">
        <h2>Meet the Team</h2>
        <div class="team">
            <div class="member">
                <img src="carillo.jpg" alt="Member 1">
                <h3>Gabriella Micca A. Carillo</h3>
                <p>BSIT 3B G2</p>
            </div>
            <div class="member">
                <img src="tepace.jpg" alt="Member 2">
                <h3>Brent D. Tepace</h3>
                <p>BSIT 3B G2</p>
            </div>
        </div>
    </div>

    <script>
    function toggleDropdown() {
        const dropdown = document.getElementById('logoutDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    window.addEventListener('click', function (e) {
        if (!e.target.closest('.userMenu')) {
            document.getElementById('logoutDropdown').style.display = 'none';
        }
    });
</script>

</body>

</html>
