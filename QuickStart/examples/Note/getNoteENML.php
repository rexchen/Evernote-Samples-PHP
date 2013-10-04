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

$noteGuid = '9792becf-08a9-4ccd-a106-10d9afcfe621';//'3882acea-0378-4727-a68e-f2816db6f290';//'388ed7b3-4337-4693-87cd-9fe48ac1b33c';//'a8c3e7a5-cfda-4cb1-a774-a326f510815b';

/* getNote(noteGuid, withContent, withResourcesData, withResourcesRecognition, withResourcesAlternateData) */
$note = $client->getNoteStore()->getNote($noteGuid, true, true, true, true);

echo '<textarea style="width: 100%; height: 100%;">'.$note->content.'</textarea>';
