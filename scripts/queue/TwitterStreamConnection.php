<?php
require_once '../bootstrap.php';

$protocol = 'http://';
$url = 'stream.twitter.com/1/statuses/sample.json';
$username = $configAr['twitter']['username'];
$password = $configAr['twitter']['password'];

$authURL = $protocol . $username . ':' . $password . '@' . $url;

// Set up 0MQ pipeline

$context = new ZMQContext();
$sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$sender->bind('tcp://*:5557');

// Set Up Twitter Connection

$handle = fopen($authURL, 'r');


if($handle)
{
    while(($line = fgets($handle)) !== FALSE)
    {
        echo "Sending tweet to worker... \n";
        $sender->send($line);
    }
}


?>
