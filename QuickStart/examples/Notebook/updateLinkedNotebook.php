<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\Types\Notebook, EDAM\Types\SharedNotebook;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

/*
    updateLinkedNotebook method only updates the "name" of a linked notebook

    Change linkedNotebook Name A to B
*/

$name = 'LinkedNotebook';
$changeName = 'Updated LinkedNotebook';

$notebooks = $client->getNoteStore()->listLinkedNotebooks();
if (!empty($notebooks)) {
    foreach ($notebooks as $notebook) {
        if($notebook->shareName = $name){
            $notebook->shareName = $changeName;
            try {
                $updateSequenceNumber = $client->getNoteStore()->updateLinkedNotebook($notebook);
                echo 'Upadte linked notebook successfully. ('.$updateSequenceNumber.')';
            }
            catch (EDAMUserException $e) {
                echo 'Upadte linked notebook failed.';
            }
            break;
        }
    }
}