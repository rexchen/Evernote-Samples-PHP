<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode;
use EDAM\Types\Notebook, EDAM\Types\SharedNotebook, EDAM\Types\LinkedNotebook;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebook = new Notebook();
$notebook->name = 'Create Share Notebook';

try {
    $newNoteBook = $client->getNoteStore()->createNotebook($notebook);

    $user = $client->getUserStore()->getUser();
    
    $sharedNotebook = new SharedNotebook();
    $sharedNotebook->notebookGuid = $newNoteBook->guid;
    $sharedNotebook->email = 'rex@huijun.org';
    $sharedNotebook->privilege = 1; // modify
    $sharedNotebook->allowPreview = false;
    $share = $client->getNoteStore()->createSharedNotebook($sharedNotebook);

    echo $newNoteBook->name.' created successfully.';
    echo '<pre>';
    print_r($share);
    echo '</pre>';
}
catch (EDAMUserException $e) {
    echo $notebook->name.' created failed';
}