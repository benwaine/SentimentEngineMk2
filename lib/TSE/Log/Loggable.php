<?php
/**
 * Description of Logable
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
interface TSE_Log_Loggable {

    public function loggerAttached();

    public function attachLogger(TSE_Log_Logger $logger);

}

