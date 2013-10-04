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

//try {
    //9e4145b1-fb2a-43f8-a69c-12bb81a4db1e
    //$newNoteBook = $client->getNoteStore()->createNotebook($notebook);
    
    $user = $client->getUserStore()->getUser();
    
    $sharedNotebook = new SharedNotebook();
    $sharedNotebook->notebookGuid = '9e4145b1-fb2a-43f8-a69c-12bb81a4db1e'; //$newNoteBook->guid;
    $sharedNotebook->email = 'rex@huijun.org';
    $sharedNotebook->privilege = 1; // modify
    $sharedNotebook->allowPreview = false;
    $share = $client->getNoteStore()->createSharedNotebook($sharedNotebook);

    $receiver = array('rex@huijun.org');

    echo '<pre>';
    print_r($share);
    echo '</pre>';

    /*
    $linkedNotebook = new LinkedNotebook();
    $linkedNotebook->shareName = $notebook->name;
    $linkedNotebook->username = $user->username;
    //$linkedNotebook->uri
    $linkedNotebook->shardId = '26865';//$share->id;
    $linkedNotebook->shareKey = '68f1-s93-1a4c4523d32ff8338581af364c00e8a2-2';//$share->shareKey;
    try{
        $link = $client->getNoteStore()->createLinkedNotebook($linkedNotebook);
    }
    catch (EDAMUserException $e){
        echo EDAMErrorCode::$__names[$e->errorCode];
    }
    */

    
    try {
        $client->getNoteStore()->sendMessageToSharedNotebookMembers(
            $share->notebookGuid,
            '',
            array()
        );
    }
    catch (EDAMUserException $e){
        echo EDAMErrorCode::$__names[$e->errorCode];
    }