<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 11:29
 */

namespace Tests\Afw\Component\Migration\Service;


use Afw\Component\Migration\Service\MigrateStrategyInterface;
use Afw\Component\Migration\Service\Migrator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

final class MigratorTest extends TestCase
{
    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function testMigrate()
    {
        $connection = $this->createMock(Connection::class);
        $schemaManager = $this->createMock(AbstractSchemaManager::class);
        $schemaManager->method('tablesExist')->willReturn(true);
        $connection->method('getSchemaManager')->willReturn($schemaManager);
        $connection->method('fetchAll')->willReturn([]);

        $strategy = $this->createMock(MigrateStrategyInterface::class);
        $strategy->expects(static::once())->method('execute');
        $output = $this->createMock(OutputInterface::class);
        $migrator = new Migrator($strategy, $connection, $output);

        $migrator->migrate();
    }
}