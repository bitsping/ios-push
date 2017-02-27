<?php
namespace BitsPing\Push;

class iosPush {
    /*
    Setup private key's passphrase here 
    */
    private $passPhrase;
    /*
    Array of device token's [device_1,device_2...]
    */
    public $deviceTokens = [];
    /*
    Stream context
    */
    protected $ctx;
    /*
    File pointer: connection with APN server
    */
    protected $filePointer=0;
    /*
    Name /Path to .PEM file
    */
    protected $pemFile;
    /*
    Sound : sound of notification "default"
    */
    public $sound = 'default';
    /*
    Payload : Json encoded message array to APN
    */
    public $payLoad;
    /*
    gateway : URL to APN
    */
    public $gateWay;    
    /*
    Setup the connection with credncials
    */
    public function __construct($passPhrase,$pem,$sound="default",$gateWay="ssl://gateway.sandbox.push.apple.com:2195"){
        $this->setPassphrase($passPhrase);
        $this->setPem($pem);
        $this->setSound($sound);
        $this->setGateway($gateWay);
    }
    public function prepare($deviceToken=[],$message,$category="general"){        
        $this->getConnection();     
        $body['aps'] = array(
            'alert' => $message,
            'sound' => $this->sound,
            'category' => $category,
            );
        $this->deviceTokens = $deviceToken;
        $this->payLoad = json_encode($body);
    }
    public function send(){
        if($this->filePointer){
            foreach($this->deviceTokens as $token){
            $msg = chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($this->payLoad)) . $this->payLoad;
            // Send it to the server
            $result = fwrite($this->filePointer, $msg, strlen($msg));
                if(!$result){
                    throw new Exception("Failed to send : $token" . PHP_EOL);
                }
            }
            return true;
        }
        throw new Exception("System not prepared for sending " . PHP_EOL);
    }
    private function getConnection(){
        $this->ctx = stream_context_create();
        stream_context_set_option($this->ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($this->ctx, 'ssl', 'passphrase', $this->passPhrase);
        $this->filePointer = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195',
                                   $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT,
                                   $this->ctx);
        if($this->filePointer){
            return true;
        }
        throw new Exception("Failed to connect: $err $errstr" . PHP_EOL);
    }
    public function setPassphrase($passPhrase){
        return $this->passPhrase = $passPhrase;
    }
    public function setPem($pem){
        return $this->pemFile = $pem;
    }
    public function setGateway($gateway){
        return $this->gateWay = $gateway;
    }    
    public function setSound($sound){
        return $this->sound = $sound;
    }
    public function __destruct(){
        if($this->filePointer){
            fclose($this->filePointer);
        }
    }
}
?>