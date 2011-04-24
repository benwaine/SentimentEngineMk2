<?php
require_once 'bootstrap.php';

$http = new Zend_Http_Client();
$sampler = new TSE_Sampler($configAr['twitter'], $http);

$sample = $sampler->getSample('holiday', 500);

$config = new Zend_Config_Ini(realpath('../config/tokenizer.ini'), 'search');

$tokenizer = new TSE_Tokenizer($config->toArray());

$filterGenerator = new TSE_FilterGenerator($tokenizer);

$probArray = $filterGenerator->createFilter($sample);

$keywordMapper = new TSE_Mapper_Keyword($pdo);
$result = $keywordMapper->insertKeyword('holiday');

$filterMapper = new TSE_Mapper_Filter($pdo, $config->toArray());
$filterMapper->insertFilterForKeyword(1, $probArray);

$filter = $filterMapper->getFilterByKeyword('holiday');

$samples = array(
    'I love going on holiday',
    'I hate holidays',
    "I can't wait to go on holiday"
);

foreach($samples as $s)
{
    var_dump($filter->classify($s));
}


?>
