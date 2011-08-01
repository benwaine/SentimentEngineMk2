<?php 

require_once '../setup.php';

$db = $bootstrap->getResource('db');
$logger = $bootstrap->getResource('log');
$configAr = $bootstrap->getOptions();

$config = new Zend_Config_Ini(realpath('../../config/tokenizer.ini'), 'search');

$http = new Zend_Http_Client();

$sampler = new TSE_Sampler($configAr['twitter'], $http);
$sampler->attachLogger($logger);

$sample = $sampler->getSample('holiday', 500);

$tokenizer = new TSE_Tokenizer($config->toArray());

$filterGenerator = new TSE_FilterGenerator($tokenizer);

$probArray = $filterGenerator->createFilter($sample);

TSE_Debug_FilterMapper::filterToDB($probArray);

$filter = new TSE_Filter($probArray, $tokenizer);

$context = new ZMQContext();

$reciever = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$reciever->connect('tcp://localhost:5563');


echo "Standing by for tweets... \n";

while(true)
{

    $key = $reciever->recv();
    $msg = $reciever->recv();

    echo $key . ' : '  . $msg . "\n";
    var_dump($filter->classify($msg));

    
}

