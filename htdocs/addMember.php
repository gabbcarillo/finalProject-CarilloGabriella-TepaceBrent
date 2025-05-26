<?php
session_start();
$username = $_SESSION['username'] ?? 'User';

$added = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $xmlFile = 'members.xml';

    if (!file_exists($xmlFile)) {
        $students = new SimpleXMLElement('<students></students>');
    } else {
        $students = simplexml_load_file($xmlFile);
    }

   
    $newStudent = $students->addChild('student');
    $photoName = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoName = basename($_FILES['photo']['name']);
        $photoPath = 'photos/' . $photoName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
        $newStudent->addChild('photo', $photoPath); 
    } else {
        $newStudent->addChild('photo', ''); 
    }


    $newStudent->addChild('first_name', $_POST['first_name']);
    $newStudent->addChild('middle_initial', $_POST['middle_initial']);
    $newStudent->addChild('last_name', $_POST['last_name']);
    $newStudent->addChild('student_id', $_POST['student_id']);
    $newStudent->addChild('email', $_POST['email']);
    $newStudent->addChild('course', $_POST['course']);
    $newStudent->addChild('date_joined', $_POST['date_joined']);

    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($students->asXML());
    $dom->save($xmlFile);

    $added = true;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f9fafb;
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

        .container {
            padding: 40px 5%;
        }

        .formBox {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .photoUpload {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .photoUpload label {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            overflow: hidden;
            position: relative;
        }

        .photoUpload label span {
            color: #6b7280;
            font-size: 14px;
            text-align: center;
        }

        .photoUpload input {
            display: none;
        }

        .photoUpload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            display: none;
        }

        form label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        .nameGroup {
            display: flex;
            gap: 10px;
        }

        .nameGroup input {
            flex: 1;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #1E1E3F;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2f2f6c;
        }

        .logout-btn {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
            font-size: 14px;
            padding: 6px 12px;
            width: 100%;
            text-align: left;
        }

        .logout-btn:hover {
            background-color: #f3f4f6;
            border-radius: 4px;
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
           <a href="home.php" >Home</a>
            <a href="lists.php" >Members</a>
            <a href="addMember.php" class="active">Add</a>
            <a href="aboutUs.php">About Us</a>
        </nav>

        <div class="userMenu" onclick="toggleDropdown()">
            <?= htmlspecialchars($username) ?>
            <div class="dropdown" id="logoutDropdown">
                <form action="logout.php" method="post">
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="formBox">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="photoUpload">
                    <label for="photo">
                        <img id="preview" src="" alt="Preview" />
                        <span id="placeholder-text">Add Photo</span>
                        <input type="file" id="photo" name="photo" accept="image/*" required>
                    </label>
                </div>

                <div class="nameGroup">
                    <div>
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" id="first_name" required>
                    </div>
                    <div>
                        <label for="middle_initial">Middle Initial</label>
                        <input type="text" name="middle_initial" id="middle_initial" maxlength="1">
                    </div>
                    <div>
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" id="last_name" required>
                    </div>
                </div>

                <label for="student_id">Student ID</label>
                <input type="text" name="student_id" id="student_id" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="course">Program, Year & Section</label>
                <input type="text" name="course" id="course" required>

                <label for="date_joined">Date Joined</label>
                <input type="date" name="date_joined" id="date_joined" required>

                <button type="submit">Add Student</button>
            </form>
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

        const photoInput = document.getElementById('photo');
        const preview = document.getElementById('preview');
        const placeholderText = document.getElementById('placeholder-text');

        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholderText.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <?php if ($added): ?>
        <script>
            alert("A new member has been added.");
            window.location.href = "lists.php";
        </script>
    <?php endif; ?>
    
</body>

</html>