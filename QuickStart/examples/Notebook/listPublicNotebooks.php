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
$detailResult = array();
if (!empty($notebooks)) {
    foreach ($notebooks as $notebook) {
        if($notebook->published){
            $result[] = $notebook->name;
            $detailResult[] = $notebook;
        }
    }
}

echo '<pre>';
print_r($result);
echo '</pre>';
echo '<pre>';
print_r($detailResult);
echo '</pre>';