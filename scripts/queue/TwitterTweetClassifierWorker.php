<?php 

require_once '../bootstrap.php';

$config = new Zend_Config_Ini(realpath('../../config/tokenizer.ini'), 'search');

$http = new Zend_Http_Client();
$sampler = new TSE_Sampler($configAr['twitter'], $http);

$sample = $sampler->getSample('holiday', 500);

$tokenizer = new TSE_Tokenizer($config->toArray());

$filterGenerator = new TSE_FilterGenerator($tokenizer);

$probArray = $filterGenerator->createFilter($sample);

$filter = new TSE_Filter($probArray, $tokenizer);

$context = new ZMQContext();

$reciever = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$reciever->connect('tcp://localhost:5563');
$reciever->setSockOpt(ZMQ::SOCKOPT_SUBSCRIBE, "holiday");

echo "Standing by for tweets... \n";

while(true)
{

    $address = $subscriber->recv();
    $msg = $subscriber->recv();

    echo $msg . "\n";

    
}

