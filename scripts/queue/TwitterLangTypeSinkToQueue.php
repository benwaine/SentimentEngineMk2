<?php
require_once '../setup.php';

$db = $bootstrap->getResource('db');
$log = $bootstrap->getResource('log');
$configAr = $bootstrap->getOptions();

$log->info('Opening Lan > Que');

$context = new ZMQContext();

$frontEnd = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$frontEnd->bind('tcp://*:5560');

$backEnd = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$backEnd->bind('tcp://*:5561');

new ZMQDevice(ZMQ::DEVICE_QUEUE, $frontEnd, $backEnd);


?>
