<?php
require_once '../../vendor/autoload.php';
use Evernote\Client;
session_start();

$client = new Client(array(
    'token' => $_SESSION['accessToken'],
    'sandbox' => FALSE
));

$url = 'https://www.evernote.com/pub/ezplan/appTrunksPhoto';

$key = explode('/', $url);
$publicUri = $key[sizeof($key)-1];
$userName = $key[sizeof($key)-2];

$user = $client->getUserStore()->getPublicUserInfo($userName);
$notebook = $client->getNoteStore()->getPublicNotebook($user->userId, $publicUri);

echo '<pre>';
print_r($notebook);
echo '</pre>';