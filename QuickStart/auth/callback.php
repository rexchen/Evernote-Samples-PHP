<?php
session_start();

if (isset($_SESSION['opauth'])) {
    $_SESSION['accessToken'] = $_SESSION['opauth']['auth']['credentials']['token'];
    $_SESSION['shardId'] = $_SESSION['opauth']['auth']['info']['shardId'];
    echo '<pre>';
    print_r($_SESSION['opauth']);
    echo '</pre>';
}