<?php
/**
 * Description of Abstract
 *
 * @package
 * @subpackage
 * @author Ben Waine <ben@ben-waine.co.uk>
 */
abstract class TSE_Mapper_Abstract
{
    /**
     * PDO Connection
     *
     * @var PDO
     */
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

}

