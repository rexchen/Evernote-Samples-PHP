<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebook = $client->getNoteStore()->getDefaultNotebook();

echo '<pre>';
print_r($notebook);
echo '</pre>';