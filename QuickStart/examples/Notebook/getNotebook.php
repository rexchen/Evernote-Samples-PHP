<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

//getNotebook by notebookGuid
//getNotebook by name
$guid = '8bb97c7b-a3a7-4b8d-9702-643b7ba58029';

$notebook = $client->getNoteStore()->getNotebook($guid);

echo '<pre>';
print_r($notebook);
echo '</pre>';