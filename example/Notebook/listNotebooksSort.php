<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebooks = $client->getNoteStore()->listNotebooks();
$result = array();
if (!empty($notebooks)) {
    foreach ($notebooks as $notebook) {
        $result[] = $notebook->name;
    }
}
natcasesort($result);
echo '<pre>';
print_r($result);
echo '</pre>';