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
$notebookGuid = '845a4ed7-2328-4d94-984e-29fa1b374722';

$result = array();
$filter = new NoteFilter();
$filter->notebookGuid = $notebookGuid;
$offset = 0;
$spec = new NotesMetadataResultSpec();

do {
    $notesList = $client->getNoteStore()->findNotesMetadata($filter, $offset, 20, $spec);
    $offset = $notesList->startIndex + count($notesList->notes);
    $remain = $notesList->totalNotes - $offset;

    foreach ($notesList->notes as $note) {
        $fullNote = $client->getNoteStore()->getNote($note->guid, true, true, true, true);
        foreach ($fullNote->resources as $resource) {
            switch ($resource->mime) {
                case 'image/gif':
                case 'image/jpeg':
                case 'image/png':
                case 'image/bmp':
                    $result[] = $resource;
                    break;
                default:
                    break;
            }
        }
    }

} while($remain>0);

echo '<pre>';
print_r($result);
echo '</pre>';