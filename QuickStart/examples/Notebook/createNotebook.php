<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\Types\Notebook;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebook = new Notebook();
$notebook->name = 'Create Test Notebook';
//optional
$notebook->defaultNotebook = false;
$notebook->stack = 'Test Stack';

try {
    $newNoteBook = $client->getNoteStore()->createNotebook($notebook);
    echo $newNoteBook->name.' created successfully.';
    echo '<pre>';
    print_r($newNoteBook);
    echo '</pre>';
}
catch (EDAMUserException $e) {
    echo $notebook->name.' created failed';
}