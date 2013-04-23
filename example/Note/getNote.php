<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\NoteStore\NoteFilter, EDAM\NoteStore\NotesMetadataResultSpec;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$noteGuid = 'a8c3e7a5-cfda-4cb1-a774-a326f510815b';

/* getNote(noteGuid, withContent, withResourcesData, withResourcesRecognition, withResourcesAlternateData) */
$note = $client->getNoteStore()->getNote($noteGuid, true, true, true, true);

echo '<pre>';
print_r($note);
echo '</pre>';