<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 10:02
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Filesystem\Filesystem;
use Afw\Component\Migration\Migration;
use Afw\Component\Migration\Mode;
use Afw\Component\Reflection\Reflector;
use Doctrine\DBAL\Connection;

abstract class AbstractMigrationStrategy implements MigrateStrategyInterface
{
    /**
     * @var Mode
     */
    private $mode;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var string
     */
    private $migrationsPath;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Reflector
     */
    private $reflector;

    /**
     * AbstractMigrationStrategy constructor.
     * @param Mode $mode
     * @param Connection $connection
     * @param Filesystem $filesystem
     * @param Reflector $reflector
     * @param string $migrationsPath
     */
    final public function __construct(
        Mode $mode,
        Connection $connection,
        Filesystem $filesystem,
        Reflector $reflector,
        string $migrationsPath
    )
    {
        $this->mode = $mode;
        $this->connection = $connection;
        $this->migrationsPath = $migrationsPath;
        $this->filesystem = $filesystem;
        $this->reflector = $reflector;
    }

    /**
     * @return string
     */
    public function getMigrationsPath(): string
    {
        return $this->migrationsPath;
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
     * @return Reflector
     */
    public function getReflector(): Reflector
    {
        return $this->reflector;
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

    /**
     * @return array
     */
    protected function getMigrations(): array
    {
        return $this->filesystem->getClassesFromDirectory($this->migrationsPath);
    }
}