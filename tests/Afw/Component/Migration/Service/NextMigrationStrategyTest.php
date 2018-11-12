<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 11:42
 */

namespace Tests\Afw\Component\Migration\Service;


use Afw\Component\Filesystem\Filesystem;
use Afw\Component\Migration\Migration;
use Afw\Component\Migration\Mode;
use Afw\Component\Migration\Service\MigrateStrategyInterface;
use Afw\Component\Migration\Service\NextMigrationStrategy;
use Afw\Component\Reflection\Reflector;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;

final class NextMigrationStrategyTest extends TestCase
{
    /**
     * @var MigrateStrategyInterface|NextMigrationStrategy
     */
    private $strategy;
    /**
     * @var Connection|MockObject
     */
    private $connection;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Reflector
     */
    private $reflector;

    /**
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \ReflectionException
     */
    public function testExecute()
    {
        $output = $this->createMock(OutputInterface::class);
        $versions = [
            'MigrationOne',
            'MigrationTwo',
        ];
        $this->filesystem->method('getClassesFromDirectory')->willReturn([
            'MigrationOne',
            'MigrationTwo',
            'MigrationThree',
        ]);

        $this->reflector->method('initialize')->willReturnCallback(
            function ($class, $args) {
                $class = new class($args[0]) extends Migration
                {
                    public function up()
                    {
                        // TODO: Implement up() method.
                    }

                    public function down()
                    {
                        // TODO: Implement down() method.
                    }
                };

                return $class;
            }
        );

        $this->connection->expects(static::once())->method('executeQuery');

        $this->strategy->execute($versions, $output);
    }

    public function testPrepareVersions()
    {
        $versions = [1, 2, 3];

        $preparedVersions = $this->strategy->prepareVersions($versions);
        static::assertSame($versions, $preparedVersions);
    }

    protected function setUp()
    {
        parent::setUp();
        $mode = new Mode(Mode::NEXT_MODE);
        $this->connection = $this->createMock(Connection::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->reflector = $this->createMock(Reflector::class);

        $this->strategy = new NextMigrationStrategy($mode, $this->connection, $this->filesystem, $this->reflector, '/', 'App');
    }
}