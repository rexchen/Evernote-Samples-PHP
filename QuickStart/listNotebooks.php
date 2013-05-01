<?php
require_once './vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$notebooks = $client->getNoteStore()->listNotebooks();
if (!empty($notebooks)) {
    foreach ($notebooks as $notebook) {
        echo $notebook->name.'<br>';
    }
}