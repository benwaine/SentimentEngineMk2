<?php
require_once '../setup.php';

$db = $bootstrap->getResource('db');
$configAr = $bootstrap->getOptions();

$config = new Zend_Config_Ini(realpath('../../config/tokenizer.ini'), 'search');

$tokenizer = new TSE_Tokenizer($config->toArray());

$context = new ZMQContext();

$reciever = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$reciever->connect('tcp://localhost:5561');


$publisher = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$publisher->bind("tcp://*:5563");

$c = 0;

while(true)
{
    $msg = $reciever->recv();

    $ar = json_decode($msg, true);

    $watched = array('holiday');

    $tokens = $tokenizer->tokenizse($ar['text']);
    echo ++$c . ' : ' . $msg . "\n";

    foreach($watched as $watch)
    {
        if(in_array($watch, $tokens))
        {   

            echo ' s ';
            $publisher->send($watch, ZMQ::MODE_SNDMORE);
            $publisher->send($msg);
        }
    }
}


?>