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
use Symfony\Component\HttpFoundation\File\File;

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
     * AbstractMigrationStrategy constructor.
     * @param Mode $mode
     * @param Connection $connection
     * @param string $migrationsPath
     */
    final public function __construct(
        Mode $mode,
        Connection $connection,
        string $migrationsPath
    )
    {
        $this->mode = $mode;
        $this->connection = $connection;
        $this->migrationsPath = $migrationsPath;
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
        $migrations = array_filter(scandir($this->migrationsPath), function ($migrationFile) {
            return !in_array($migrationFile, ['.', '..']);
        });

        return array_values($migrations);
    }

    protected function getClassNameFromFile(File $file)
    {
        $filename = $file->getFilename();
        $pathInfo = pathinfo($filename);
        return $pathInfo['filename'];
    }
}