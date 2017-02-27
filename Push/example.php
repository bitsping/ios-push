<?php
use \BitsPing\Push\iosPush;

$passphrase = "YOUR_PASSPHRASE";
$pemFile = "PATH_TO_PEM_FILE";
$sound = "default";
$Gateway = "ssl://gateway.sandbox.push.apple.com:2195";

$deviceToken =["TOKEN_WITHOUT_SPACE","TOKEN_2","..."];


$bpush = new iosPush($passphrase,$pemFile,$sound,$Gateway);
$bpush->prepare($deviceToken,"YOUR MESSAGE","APP_CATEGORY");
$bpush->send();
?>