<?php

/**
 * Description of Botstrap
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public static $db;

    protected function _initAutoload()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('TSE_');
    }

    protected function _initDb()
    {
        $configAr = $this->getOption('db');

        $dsn = 'mysql:host=%s;port=%s;dbname=%s';
        $rDsn = sprintf($dsn, $configAr['host'], $configAr['port'], $configAr['dbname']);

        $pdo = new PDO($rDsn, $configAr['username'], $configAr['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        TSE_Debug_FilterMapper::$db = $pdo;
        return $pdo;
    }

    protected function _initLog()
    {
        $this->bootstrap('Autoload');
        $configAr = $this->getOption('log');
        $logger = new TSE_Log_Logger($configAr['path']);

        return $logger;
    }


}

