<?php

$context = new ZMQContext();

$frontEnd = new ZMQSocket($context, ZMQ::SOCKET_PULL);
$frontEnd->bind('tcp://*:5560');

$backEnd = new ZMQSocket($context, ZMQ::SOCKET_PUSH);
$backEnd->bind('tcp://*:5561');

new ZMQDevice(ZMQ::DEVICE_QUEUE, $frontEnd, $backEnd);


?>
