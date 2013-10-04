<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebooks = $client->getNoteStore()->listLinkedNotebooks();
$result = array();
$detailResult = array();
if (!empty($notebooks)) {
    foreach ($notebooks as $notebook) {
        $shareNoteStore = $client->getSharedNoteStore($notebook);
        $sharedNotebook = $shareNoteStore->getSharedNotebookByAuth();
        $linkedNotebook = $shareNoteStore->getNotebook($sharedNotebook->notebookGuid);
        $result[] = $notebook->shareName;
        $detailResult[] = $linkedNotebook;
    }
}

echo '<pre>';
print_r($result);
echo '</pre>';
echo '<pre>';
print_r($detailResult);
echo '</pre>';