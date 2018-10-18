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
use Symfony\Component\Console\Output\OutputInterface;

abstract class Migration
{
    private $statements = [];
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var OutputInterface
     */
    private $output;

    public function __construct(Connection $connection, OutputInterface $output)
    {
        $this->connection = $connection;
        $this->output = $output;
    }

    abstract public function up();

    abstract public function down();

    public function addExpression(string $sql)
    {
        $this->statements[] = $sql;
    }

    public function execute()
    {
        $this->connection->beginTransaction();
        try {
            foreach ($this->statements as $statement) {
                $this->connection->exec($statement);
            }
            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            $this->output->writeln($e);
        }
    }
}