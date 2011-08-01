<?php
/**
 * Description of Logger
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
class TSE_Log_Logger
{

    protected $logger;

    public function __construct($path)
    {
        $writer = new Zend_Log_Writer_Stream($path);
        $this->logger = new Zend_Log($writer);
    }

    public function debug($message)
    {
        $this->logger->debug($message);
    }

    public function info($message)
    {
        $this->logger->info($message);
    }

    public function warn($message)
    {
        $this->logger->warn($message);
    }

    public function error($message)
    {
        $this->logger->error($message);
    }
}

