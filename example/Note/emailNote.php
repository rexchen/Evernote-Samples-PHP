<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException, EDAM\Error\EDAMErrorCode;
use EDAM\Types\Note, EDAM\NoteStore\NoteEmailParameters;
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
$note->title = 'Email Note';
$note->content = $content;

$emailParameter = new NoteEmailParameters();
$emailParameter->guid = '09733163-f421-46c5-88d4-84328232eb03';
//$emailParameter->note = $note;
$emailParameter->toAddresses = array('rex@huijun.org');
$emailParameter->ccAddresses = array('animal1004@hotmail.com');
$emailParameter->subject = 'Email Note';
$emailParameter->message = 'description';

try {
    $newNote = $client->getNoteStore()->emailNote($emailParameter);
    echo $newNote->title.' email sent successfully.';
}
catch (EDAMUserException $e) {
    echo $note->title.' email sent failed<br>';
    echo EDAMErrorCode::$__names[$e->errorCode];
}

