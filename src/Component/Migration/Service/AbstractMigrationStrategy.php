<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 10:02
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Migration\Migration;
use Afw\Component\Migration\Mode;
use Doctrine\DBAL\Connection;

abstract class AbstractMigrationStrategy
{
    /**
     * @var Mode
     */
    private $mode;
    /**
     * @var Connection
     */
    private $connection;

    final public function __construct(
        Mode $mode,
        Connection $connection
    )
    {
        $this->mode = $mode;
        $this->connection = $connection;
    }

    /**
     * @return Mode
     */
    public function getMode(): Mode
    {
        return $this->mode;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param Migration $migration
     * @return string
     * @throws \ReflectionException
     */
    final protected function getBaseMigrationClass(Migration $migration): string
    {
        return (new \ReflectionClass($migration))->getShortName();
    }
}