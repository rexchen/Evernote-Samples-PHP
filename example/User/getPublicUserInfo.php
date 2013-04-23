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
$userName = $key[sizeof($key)-2];

$user = $client->getUserStore()->getPublicUserInfo($userName);

echo '<pre>';
print_r($user);
echo '</pre>';