<?php
require_once '../setup.php';

$db = $bootstrap->getResource('db');
$log = $bootstrap->getResource('log');
$configAr = $bootstrap->getOptions();

$keywordMapper = new TSE_Mapper_keyword($db);

$keywords = $keywordMapper->getKeywordsAsText();

$query = '?track=' . implode(',', $keywords);

$protocol = 'http://';
$url = 'stream.twitter.com/1/statuses/filter.json';
$username = $configAr['twitter']['username'];
$password = $configAr['twitter']['password'];

$authURL = $protocol . $username . ':' . $password . '@' . $url . $query;

// Set up 0MQ pipeline

$context = new ZMQContext();
$sender = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$sender->bind('tcp://*:5557');

// Set Up Twitter Connection
$log->info('Opening A New Twitter Stream: ' . $authURL);

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
