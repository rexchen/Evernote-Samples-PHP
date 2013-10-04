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
input:  notebook guid
        user email
*/
$guid = '8b2b6cc3-4417-45da-8c07-da1f140a5635';
$email = 'rex@huijun.org';

$notebook = $client->getNoteStore()->getNotebook($guid);

$sharedNotebooks = $notebook->sharedNotebooks;
if (!empty($sharedNotebooks)) {
    foreach ($sharedNotebooks as $sharedNotebook) {
        if($sharedNotebook->email = $email){
            /*
                $sharedNotebook must be set field:
                id
                email
                privilege
                allowPreview
            */
            $sharedNotebook->privilege = 2; // read
            try {
                $updateSequenceNumber = $client->getNoteStore()->updateSharedNotebook($sharedNotebook);
                echo 'Upadte shared notebook successfully. ('.$updateSequenceNumber.')';
            }
            catch (EDAMUserException $e) {
                echo 'Upadte shared notebook failed.';
            }
            break;
        }
    }
}