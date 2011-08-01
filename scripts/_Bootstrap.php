<?php
// Inlude Path
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), realpath(__DIR__ . '/../lib'))));

define('APPLICATION_ENV', 'development');

ini_set('html_errors', 0);
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('TSE_');

$config = new Zend_Config_Ini(realpath(__DIR__ . '/../config/app.ini'), APPLICATION_ENV );
$configAr = $config->toArray();

// SET UP DB CONNECTION

$dsn = 'mysql:host=%s;port=%s;dbname=%s';
$rDsn = sprintf($dsn, $configAr['db']['host'], $configAr['db']['port'], $configAr['db']['dbname']);

$pdo = new PDO($rDsn, $configAr['db']['username'], $configAr['db']['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
