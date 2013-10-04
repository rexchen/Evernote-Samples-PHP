<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\Types\Note, EDAM\Types\NoteAttributes;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$content = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">
<en-note style="
padding: 20px;
background-color: #fff;
">
<div style="
position: relative;
box-shadow: 0 1px 4px hsla(0,0%,0%,.25);
background: #faf8e5;
background-image: -webkit-radial-gradient(#e6e6e6 21%, transparent 21%),
-webkit-radial-gradient(hsla(0,0%,0%,.25) 21%, transparent 26%),
-webkit-linear-gradient(top, hsla(0,0%,0%,0) 0%, hsla(0,0%,0%,0) 95%, hsla(180,25%,50%,.1) 95%, rgba(0,0,0,.1) 100%);
background-position: 6px 6px, 6px 5px, 50% 18px;
background-repeat: repeat-y, repeat-y, repeat;
background-size: 48px 48px, 48px 48px, 24px 24px;
padding: 18px 24px 24px 84px;
line-height: 24px;
-webkit-radial-gradient(hsla(0,0%,0%,.25) 21%, transparent 26%);
-webkit-linear-gradient(top, hsla(0,0%,0%,0) 0%, hsla(0,0%,0%,0) 95%;
hsla(180,25%,50%,.1) 95%, hsla(180,25%,50%,.1) 100%);
min-height: 200px;">
<div style="
position: absolute;
border-left: 1px solid hsla(0,75%,50%,.4);
border-right: 1px solid hsla(0,75%,50%,.4);
bottom: 0;
left: 58px;
top: 0;
width: 3px;
"></div>
<div>
The Evernote Accelerator will serve as the culmination of our 2013 developer program activities. The program started just a few weeks ago, at SxSW, when we announced the details of our 2013 Devcup competition to build great Evernote integrations. This year, in addition to awarding Devcup winners cash prizes and trips to San Francisco (for our third annual Evernote Conference), we will invite some of the best winning teams to our Accelerator program.
</div>
</div>
</en-note>
EOT;

$attribute = new NoteAttributes();
$attribute->contentClass = 'apptrunks.wowrld.journey';

$note = new Note();
$note->title = 'Test read-only note';
$note->content = $content;
$note->notebookGuid = '18462cf4-a95a-4fb6-90bd-2833c49a187d';
$note->attributes = $attribute;

try {
    $newNote = $client->getNoteStore()->createNote($note);
    echo $newNote->title.' created successfully.';
}
catch (EDAMUserException $e) {
    echo $note->title.' created failed';
}

