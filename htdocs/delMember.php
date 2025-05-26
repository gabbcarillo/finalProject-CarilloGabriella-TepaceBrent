<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $studentIdToDelete = $_POST['student_id'];
    $xmlFile = 'members.xml';

    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);

        for ($i = 0; $i < count($xml->student); $i++) {
            if ((string)$xml->student[$i]->student_id === $studentIdToDelete) {
                unset($xml->student[$i]);

                $dom = new DOMDocument('1.0');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($xml->asXML());
                $dom->save($xmlFile);
                break;
            }
        }
    }
}

header('Location: lists.php');
exit;
?>
