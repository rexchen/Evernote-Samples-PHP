<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\Types\Notebook, EDAM\Types\Publishing;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$publishing = new Publishing();
$publishing->uri = 'PublicLink';
$publishing->publicDescription = 'Public Description';
$publishing->order = 2; //UPDATED
$publishing->ascending = false;

$notebook = new Notebook();
$notebook->name = 'Create Test Notebook';
$notebook->publishing = $publishing;
$notebook->published = true;

try {
    $newNoteBook = $client->getNoteStore()->createNotebook($notebook);
    echo $newNoteBook->name.' created successfully.';
}
catch (EDAMUserException $e) {
    echo $notebook->name.' created failed';
}