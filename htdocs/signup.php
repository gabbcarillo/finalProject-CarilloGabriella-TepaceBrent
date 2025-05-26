<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $xmlFile = 'users.xml';

    
    $firstName = htmlspecialchars($_POST['first_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $role = htmlspecialchars($_POST['role']); 
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<users></users>');
    }

    foreach ($xml->user as $user) {
        if ((string)$user->username === $username) {
            echo "Username already exists.";
            exit;
        }
    }

   
    $newUser = $xml->addChild('user');
    $newUser->addChild('first_name', $firstName);
    $newUser->addChild('last_name', $lastName);
    $newUser->addChild('role', $role); 
    $newUser->addChild('username', $username);
    $newUser->addChild('password', $password);

  
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($xml->asXML()); 
    $dom->save($xmlFile);

    header("Location: index.php?signup=success");
    exit;
}
?>
