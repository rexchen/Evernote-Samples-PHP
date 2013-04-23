<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$result = array();
$myNotebooks = $client->getNoteStore()->listNotebooks();
$sharedNotebooks = $client->getNoteStore()->listSharedNotebooks();
$linkedNotebooks = $client->getNoteStore()->listLinkedNotebooks();

if (!empty($myNotebooks)) {
    foreach ($myNotebooks as $notebook) {
        $result[] = $notebook->name;
    }
}

if (!empty($sharedNotebooks)) {
    foreach ($sharedNotebooks as $notebook) {
        $name = $client->getNoteStore()->getNotebook($notebook->notebookGuid)->name;
        $result[] = $name;
    }
}

if (!empty($linkedNotebooks)) {
    foreach ($linkedNotebooks as $notebook) {
        $shareNoteStore = $client->getSharedNoteStore($notebook);
        $sharedNotebook = $shareNoteStore->getSharedNotebookByAuth();
        $name = $shareNoteStore->getNotebook($sharedNotebook->notebookGuid)->name;
        $result[] = $name;
    }
}

$result = array_unique($result);
natcasesort($result);
echo '<pre>';
print_r($result);
echo '</pre>';