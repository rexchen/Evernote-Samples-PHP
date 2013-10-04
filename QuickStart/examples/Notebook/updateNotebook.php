<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
use EDAM\Error\EDAMUserException;
use EDAM\Types\Notebook, EDAM\Types\Publishing;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

//input
$guid = '5179020b-ea30-470e-8ffd-14da3f7cf3ed';

$publishing = new Publishing();
$publishing->uri = 'PublicLink';
$publishing->publicDescription = 'Public Description';
$publishing->order = 2; //UPDATED
$publishing->ascending = false;

$notebook = $client->getNoteStore()->getNotebook($guid);
$notebook->name = 'Update Test Notebook';
$notebook->publishing = $publishing;
$notebook->published = true;

try {
    $updateSequenceNumber = $client->getNoteStore()->updateNotebook($notebook);
    echo 'Updated successfully. ('.$updateSequenceNumber.')';
}
catch (EDAMUserException $e) {
    echo $notebook->name.' updated failed';
}