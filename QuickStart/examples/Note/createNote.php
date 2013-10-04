<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\Types\Note;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$content = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">
<en-note>Hello, World!</en-note>
EOT;

$note = new Note();
$note->title = 'Create Hello World Note';
$note->content = $content;

try {
    $newNote = $client->getNoteStore()->createNote($note);
    echo $newNote->title.' created successfully.';
}
catch (EDAMUserException $e) {
    echo $note->title.' created failed';
}

