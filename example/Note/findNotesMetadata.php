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

//input notebookGuid
$notebookGuid = '18462cf4-a95a-4fb6-90bd-2833c49a187d';

$result = array();
$filter = new NoteFilter();
$filter->notebookGuid = $notebookGuid;
$offset = 0;
$spec = new NotesMetadataResultSpec();
$spec->includeTitle = true;
$spec->includeContentLength = true;
$spec->includeCreated = true;
$spec->includeUpdated = true;
//$spec->includeDeleted = true;
$spec->includeUpdateSequenceNum = true;
$spec->includeNotebookGuid = true;
$spec->includeTagGuids = true;
$spec->includeAttributes = true;
$spec->includeLargestResourceMime = true;
$spec->includeLargestResourceSize = true;

do {
    $notesList = $client->getNoteStore()->findNotesMetadata($filter, $offset, 20, $spec);
    $offset = $notesList->startIndex + count($notesList->notes);
    $remain = $notesList->totalNotes - $offset;

    foreach ($notesList->notes as $note) {
        $result[] = $note;
    }

} while($remain>0);

echo '<pre>';
print_r($result);
echo '</pre>';