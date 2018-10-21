<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 12:19
 */

namespace Tests\Afw\Component\Migration\Service;


use Afw\Component\Filesystem\Filesystem;
use Afw\Component\Migration\Mode;
use Afw\Component\Migration\Service\MigrationStrategyFactory;
use Afw\Component\Migration\Service\NextMigrationStrategy;
use Afw\Component\Migration\Service\PrevMigrationStrategy;
use Afw\Component\Reflection\Reflector;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class MigrationStrategyFactoryTest extends TestCase
{
    /**
     * @var MigrationStrategyFactory
     */
    private $factory;
    /**
     * @var Connection
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

    public function testCreateInvalidMode()
    {
        static::expectException(\RuntimeException::class);

        $mode = new Mode('invalid_Value');
        $strategy = $this->factory->create($mode, $this->connection, $this->filesystem, $this->reflector, '/');
    }

    /**
     * @throws \ReflectionException
     */
    public function testCreateInvalidStrategyMode()
    {
        static::expectException(\RuntimeException::class);

        $rClass = new \ReflectionClass(Mode::class);
        /** @var Mode $mode */
        $mode = $rClass->newInstanceWithoutConstructor();
        $property = $rClass->getProperty('mode');
        $property->setAccessible(true);
        $property->setValue($mode, 'fsdfsdf');
        $strategy = $this->factory->create($mode, $this->connection, $this->filesystem, $this->reflector, '/');
    }

    public function testCreateNext()
    {
        $mode = new Mode(Mode::NEXT_MODE);
        $nextStrategy = $this->factory->create($mode, $this->connection, $this->filesystem, $this->reflector, '/');
        static::assertInstanceOf(NextMigrationStrategy::class, $nextStrategy);

        $mode = new Mode(Mode::PREV_MODE);
        $prevStrategy = $this->factory->create($mode, $this->connection, $this->filesystem, $this->reflector, '/');
        static::assertInstanceOf(PrevMigrationStrategy::class, $prevStrategy);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->connection = $this->createMock(Connection::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->reflector = $this->createMock(Reflector::class);

        $this->factory = new MigrationStrategyFactory();
    }
}