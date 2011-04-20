<?php
// Inlude Path
set_include_path(implode(PATH_SEPARATOR, array(get_include_path(), realpath('../lib'))));

define('APPLICATION_ENV', 'development');

ini_set('html_errors', 0);
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('TSE_');

$config = new Zend_Config_Ini(realpath('../config/app.ini'), APPLICATION_ENV );
$configAr = $config->toArray();
?>
