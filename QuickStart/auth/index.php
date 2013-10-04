<?php
require_once '../config.php'; //把composer相依的檔案都include進來

$config = array(
    'path' => AUTH_PATH, //index.php所在的路徑
    'callback_url' => AUTH_PATH.'callback.php', //callback.php所在的路徑，登入成功後，往只會倒到這裡
    'security_salt' => AUTH_SECURITY_SALT, //修改你自己的
    'Strategy' => array(
        'Evernote' => array(
            'api_key' => EVERNOTE_CONSUMER_KEY, //你的evernote consumer key
            'secret_key' => EVERNOTE_CONSUMER_SECRET, //你的evernote secret key
            'sandbox' => EVERNOTE_SANDBOX // FALSE = production, TRUE = sandbox
        )
    )
);

$Opauth = new Opauth( $config );