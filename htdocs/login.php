<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xmlFile = 'users.xml';
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!file_exists($xmlFile)) {
        echo "No users found.";
        exit;
    }

    $xml = simplexml_load_file($xmlFile);

    foreach ($xml->user as $user) {
        if ((string)$user->username === $username && password_verify($password, (string)$user->password)) {
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['role'] = (string)$user->role; 
            header("Location: home.php");
            exit;
        }
    }

     echo "<script>alert('Invalid username or password.'); window.history.back();</script>";
}
?>
