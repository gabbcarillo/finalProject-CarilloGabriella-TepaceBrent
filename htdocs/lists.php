<?php
session_start();
$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'user';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Directory</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f3f4f6;
            font-size: 14px;
            text-transform: uppercase;
        }

        td img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .actionsButton button {
            padding: 6px 10px;
            margin-right: 6px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }

        .editBtn {
            background-color: #3b82f6;
            color: white;
        }

        .deleteBtn {
            background-color: #ef4444;
            color: white;
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
            <a href="lists.php" class="active">Members</a>
            <a href="addMember.php">Add</a>
            <a href="aboutUs.php">About Us</a>
        </nav>

        <div class="userMenu" onclick="toggleDropdown()">
            <?= htmlspecialchars($username) ?>
            <div class="dropdown" id="logoutDropdown">
                <form action="logout.php" method="post">
                    <button type="submit" style="background: none; 
                        border: none; 
                        color: red; 
                        cursor: pointer;">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container">
        <div style="display: flex; 
        justify-content: space-between; 
        align-items: center; 
        margin-bottom: 20px;">
            <h2 style="font-size: 24px; font-weight: bold;">Student Members</h2>
            <?php if ($role === 'admin'): ?>
                <a href="addMember.php" style="
                background-color: #1E1E3F;
                color: white;
                padding: 10px 20px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    + Add New
                </a>
            <?php endif; ?>

        </div>

        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Full Name</th>
                    <th>Student ID</th>
                    <th>Email</th>
                    <th>Course / Program & Year</th>
                    <th>Date Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (file_exists('members.xml')) {
                    $xml = simplexml_load_file('members.xml');
                    foreach ($xml->student as $student) {

                        $first = htmlspecialchars($student->first_name ?? '');
                        $middle = htmlspecialchars($student->middle_initial ?? '');
                        $last = htmlspecialchars($student->last_name ?? '');
                        $fullName = trim($first . ' ' . $middle . '. ' . $last);

                        $photo = htmlspecialchars($student->photo);
                        $photoPath = !empty($photo) && file_exists($photo) ? $photo : 'default.png';

                        echo '<tr>';
                        echo '<td><img src="' . $photoPath . '" alt="Photo" width="50" height="50" style="border-radius: 50%; object-fit: cover;"></td>';
                        echo '<td>' . $fullName . '</td>';
                        echo '<td>' . htmlspecialchars($student->student_id) . '</td>';
                        echo '<td>' . htmlspecialchars($student->email) . '</td>';
                        echo '<td>' . htmlspecialchars($student->course) . '</td>';
                        echo '<td>' . htmlspecialchars($student->date_joined) . '</td>';
                        echo '<td class="actionsButton">';
                        if ($role === 'admin') {
                            echo '<form method="GET" action="editMember.php" style="display:inline;">
                            <input type="hidden" name="edit" value="' . htmlspecialchars($student->student_id) . '">
                            <button type="submit" class="editBtn">Edit</button>
                            </form>';

                            echo '<form method="POST" action="delMember.php" onsubmit="return confirm(\'Are you sure you want to delete this member?\');" style="display:inline;">
                            <input type="hidden" name="student_id" value="' . htmlspecialchars($student->student_id) . '">
                            <button type="submit" class="deleteBtn">Delete</button>
                            </form>';
                        } else {
                            echo '<span style="color:gray;">No actions</span>';
                        }
                        echo '</td>';

                    }
                } else {
                    echo '<tr><td colspan="7">No student records found.</td></tr>';
                }

                ?>
            </tbody>
        </table>
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