<?php
session_start();
$username = $_SESSION['username'] ?? 'User';

$xmlFile = 'members.xml';
$students = file_exists($xmlFile) ? simplexml_load_file($xmlFile) : new SimpleXMLElement('<students></students>');

$editMode = false;
$edited = false;
$added = false;


if (isset($_GET['edit'])) {
    $editMode = true;
    $editId = $_GET['edit'];
    $studentToEdit = null;

    foreach ($students->student as $student) {
        if ((string)$student->student_id === $editId) {
            $studentToEdit = $student;
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] === 'true';
    $student_id = $_POST['student_id'];

    if ($isEdit) {
        foreach ($students->student as $student) {
            if ((string)$student->student_id === $student_id) {
                $studentToUpdate = $student;
                break;
            }
        }
        if (isset($studentToUpdate)) {
            $studentToUpdate->first_name = $_POST['first_name'];
            $studentToUpdate->middle_initial = $_POST['middle_initial'];
            $studentToUpdate->last_name = $_POST['last_name'];
            $studentToUpdate->email = $_POST['email'];
            $studentToUpdate->course = $_POST['course'];
            $studentToUpdate->date_joined = $_POST['date_joined'];

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $photoName = basename($_FILES['photo']['name']);
                $photoPath = 'photos/' . $photoName;
                move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
                $studentToUpdate->photo = $photoPath;
            }

            $edited = true;
        }
    } else {
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

        $added = true;
    }

    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($students->asXML());
    $dom->save($xmlFile);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $editMode ? 'Edit Student' : 'Add Student' ?></title>
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
        .action-buttons button {
            padding: 6px 10px;
            margin-right: 6px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
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
            <a href="addMember.php">Add</a>
            <a href="aboutUs.html">About Us</a>
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
    <div class="formBox">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="is_edit" value="<?= $editMode ? 'true' : 'false' ?>">

            <div class="photoUpload">
    <label for="photo">
        <?php
        $photoSrc = $editMode && !empty($studentToEdit->photo) ? htmlspecialchars($studentToEdit->photo) : '';
        $hasPhoto = !empty($photoSrc);
        ?>
        <img id="preview" src="<?= $photoSrc ?>" alt="Preview" style="<?= $hasPhoto ? 'display:block;' : 'display:none;' ?>">
        <span id="placeholder-text" style="<?= $hasPhoto ? 'display:none;' : '' ?>">Add Photo</span>
        <input type="file" id="photo" name="photo" <?= $editMode ? '' : 'required' ?>>
    </label>
</div>


            <div class="nameGroup">
                <div>
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" required value="<?= $editMode ? htmlspecialchars($studentToEdit->first_name) : '' ?>">
                </div>
                <div>
                    <label for="middle_initial">Middle Initial</label>
                    <input type="text" name="middle_initial" id="middle_initial" maxlength="1" value="<?= $editMode ? htmlspecialchars($studentToEdit->middle_initial) : '' ?>">
                </div>
                <div>
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required value="<?= $editMode ? htmlspecialchars($studentToEdit->last_name) : '' ?>">
                </div>
            </div>

            <label for="student_id">Student ID</label>
            <input type="text" name="student_id" id="student_id" required value="<?= $editMode ? htmlspecialchars($studentToEdit->student_id) : '' ?>" <?= $editMode ? 'readonly' : '' ?>>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required value="<?= $editMode ? htmlspecialchars($studentToEdit->email) : '' ?>">

            <label for="course">Program, Year & Section</label>
            <input type="text" name="course" id="course" required value="<?= $editMode ? htmlspecialchars($studentToEdit->course) : '' ?>">

            <label for="date_joined">Date Joined</label>
            <input type="date" name="date_joined" id="date_joined" required value="<?= $editMode ? htmlspecialchars($studentToEdit->date_joined) : '' ?>">

            <button type="submit"><?= $editMode ? 'Save Changes' : 'Add Student' ?></button>
        </form>
    </div>
</div>


<?php if ($added): ?>
    <script>
        alert("A new member has been added.");
        window.location.href = "home.php";
    </script>
<?php endif; ?>

<?php if ($edited): ?>
    <script>
        alert("Student details updated.");
        window.location.href = "lists.php";
    </script>
<?php endif; ?>

<script>
document.getElementById('photo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const placeholder = document.getElementById('placeholder-text');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>


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

<footer style="text-align: center; padding: 20px; background-color: #1E1E3F; color: white; font-size: 14px;">
    &copy; <?php echo date("Y"); ?> Codeverse Student Organization. All rights reserved.
  </footer>
  
</body>
</html>
