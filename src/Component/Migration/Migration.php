<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:23
 */

namespace Afw\Component\Migration;


use Doctrine\DBAL\Connection;

abstract class Migration
{
    private $statements = [];
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    abstract public function up();

    abstract public function down();

    public function addExpression(string $sql)
    {
        $this->statements[] = $sql;
    }

    public function execute()
    {
        foreach ($this->statements as $statement) {
            $this->connection->exec($statement);
        }
    }
}