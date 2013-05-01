<?php
require_once '../vendor/autoload.php'; //把composer相依的檔案都include進來
 
$config = array(
    'path' => '/test/auth/', //index.php所在的路徑
    'callback_url' => '/test/auth/callback.php', //callback.php所在的路徑，登入成功後，往只會倒到這裡
    'security_salt' => 'LDFmiilYf8Fyw5W10rx4W1KsVrieQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4n', //修改你自己的
    'Strategy' => array(
        'Evernote' => array(
            'api_key' => 'EVERNOTE_CONSUMER_KEY', //你的evernote consumer key
            'secret_key' => 'EVERNOTE_SECRET_KEY', //你的evernote secret key
            'sandbox' => FALSE // FALSE = production, TRUE = sandbox
        )
    )
);
 
$Opauth = new Opauth($config);