<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:44
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Migration\Migration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

final class Migrator
{
    /**
     * @var MigrateStrategyInterface
     */
    private $strategy;
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var null|string
     */
    private $migrationsPath;
    /**
     * @var OutputInterface
     */
    private $output;


    /**
     * Migrator constructor.
     * @param MigrateStrategyInterface $strategy
     * @param Connection $connection
     * @param string $migrationsPath
     * @param OutputInterface $output
     */
    public function __construct(
        MigrateStrategyInterface $strategy,
        Connection $connection,
        OutputInterface $output,
        ?string $migrationsPath = null
    )
    {
        $this->strategy = $strategy;
        $this->connection = $connection;
        $this->migrationsPath = $migrationsPath ?? implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'app', 'Migrations']);
        $this->output = $output;
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    public function migrate(): void
    {
        $executedMigrations = 0;
        $versions = $this->getVersions();
        $migrations = $this->getMigrations();
        foreach ($migrations as $migration) {
            $file = new File($this->migrationsPath . DIRECTORY_SEPARATOR . $migration);
            $className = $this->getClassNameFromFile($file);

            if (false === $this->strategy->needToExecute($className, $versions)) {
                continue;
            }

            $migrationClass = "\\App\\Migrations\\${className}";
            /** @var Migration $migration */
            $migration = new $migrationClass($this->connection, $this->output);
            $this->strategy->execute($migration, $this->output);

            $executedMigrations++;
        }

        if (0 === $executedMigrations) {
            throw new \RuntimeException('Nothing to execute');
        }
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    private function getVersions(): array
    {
        $this->prepareMigrationsTable();

        $versions = $this->connection->fetchAll('SELECT * FROM migrations');

        if (false === $versions) {
            $versions = [];
        }

        $result = [];

        foreach ($versions as $version) {
            $result[] = $version['version'];
        }

        return $this->strategy->prepareVersions($result);
    }

    /**
     * @throws \Doctrine\DBAL\DBALException
     */
    private function prepareMigrationsTable(): void
    {
        $schemaManager = $this->connection->getSchemaManager();
        $isMigrationsTableExist = $schemaManager->tablesExist(['migrations']);
        if (false === $isMigrationsTableExist) {
            $type = Type::getType('string');
            $column = new Column('version', $type);

            $table = new Table('migrations', [
                $column
            ]);
            $table->addUniqueIndex(['version'], 'version_unique_indx');
            $schemaManager->createTable($table);
        }
    }

    /**
     * @return array
     */
    private function getMigrations(): array
    {

        $migrations = array_filter(scandir($this->migrationsPath), function ($migrationFile) {
            return !in_array($migrationFile, ['.', '..']);
        });

        return array_values($migrations);
    }

    private function getClassNameFromFile(File $file)
    {
        $filename = $file->getFilename();
        $pathInfo = pathinfo($filename);
        return $pathInfo['filename'];
    }
}