<?php
require_once 'bootstrap.php';

$http = new Zend_Http_Client();
$sampler = new TSE_Sampler($configAr['twitter'], $http);

$sample = $sampler->getSample('holiday', 100);

var_dump($sample);


?>
