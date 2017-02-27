# ios-push
----------


IOS push Notification from PHP server
-

----------

A simple php library for sending push notifications to APN


Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

    php composer.phar require bitsping/ios-push

Or, you may add

    "bitsping/ios-push"



Usage
-------------

    use \BitsPing\Push\iosPush;
    
    $passphrase = "YOUR_PASSPHRASE";
    $pemFile = "PATH_TO_PEM_FILE";
    $sound = "default";
    $Gateway = "ssl://gateway.sandbox.push.apple.com:2195";
    
    $deviceToken =["TOKEN_WITHOUT_SPACE","TOKEN_2","..."];
        
    $bpush = new iosPush($passphrase,$pemFile,$sound,$Gateway);
    $bpush->prepare($deviceToken,"YOUR MESSAGE","APP_CATEGORY");
    $bpush->send();



