<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebooks = $client->getNoteStore()->listSharedNotebooks();
$result = array();
$detailResult = array();
if (!empty($notebooks)) {
    foreach ($notebooks as $notebook) {
        $sharedNotebook = $client->getNoteStore()->getNotebook($notebook->notebookGuid);
        $result[] = $sharedNotebook->name;
        $detailResult[] = $sharedNotebook;
    }
}
$result = array_unique($result);
echo '<pre>';
print_r($result);
echo '</pre>';
echo '<pre>';
print_r($detailResult);
echo '</pre>';