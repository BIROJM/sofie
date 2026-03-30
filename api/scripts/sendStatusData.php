<?php
include("functions.php");

$url = 'http://192.168.120.114/sofie/api/public/sendForageStatusSynchro';
sendRequest($url, $method = 'POST');
exit;

