<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:35
 */

namespace Afw\Component\Migration\Console;


use Afw\Component\Migration\Migration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

final class MigrateCommand extends Command
{
    public const UP_MODE = 'up';
    public const DOWN_MODE = 'down';
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(
        Connection $connection
    )
    {
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure()
    {
        $this
            ->setName('db:migration:migrate')
            ->setDescription('Migrate migrates')
            ->setHelp('This command allows you to migrate')
            ->addArgument('mode', InputOption::VALUE_REQUIRED, 'up or down', self::UP_MODE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $schemaManager = $this->connection->getSchemaManager();
        $isMigrationsTableExist = $schemaManager->tablesExist(['migrations']);
        if (false === $isMigrationsTableExist) {
            $type = Type::getType('string');
            $column = new Column('version', $type, [

            ]);

            $table = new Table('migrations', [
                $column
            ]);
            $table->addUniqueIndex(['version'], 'version_unique_indx');
            $schemaManager->createTable($table);
        }
        $versions = $this->connection->fetchArray('SELECT * FROM migrations');

        if (false === $versions) {
            $versions = [];
        }

        $mode = $input->getArgument('mode');
        if (!in_array($mode, [self::UP_MODE, self::DOWN_MODE])) {
            throw new \RuntimeException('Unexpected mode');
        }
        $migrationsPath = implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'app', 'Migrations']);
        $migrations = array_filter(scandir($migrationsPath), function ($migrationFile) {
            return !in_array($migrationFile, ['.', '..']);
        });

        if ($mode === self::DOWN_MODE && !$versions) {
            throw new \RuntimeException('Nothing to rollback');
        }

        if ($mode === self::DOWN_MODE) {
            $migrations = array_reverse($migrations);
        }
        $executedMigrations = 0;
        foreach ($migrations as $migration) {
            $file = new File($migrationsPath . DIRECTORY_SEPARATOR . $migration);
            $className = $this->getClassNameFromFile($file);

            if (self::UP_MODE === $mode && in_array($className, $versions)) {
                continue;
            }

            $migrationClass = "\\App\\Migrations\\${className}";
            /** @var Migration $migration */
            $migration = new $migrationClass($this->connection, $output);
            $migration->{$mode}();

            $this->connection->beginTransaction();
            $message = $mode === self::DOWN_MODE ? 'rollbacked' : 'executed';
            try {
                $migration->execute();
                $this->connection->commit();
                $output->writeln(sprintf('<success>Migration %s successfully %s</success>', \get_class($this), $message));
            } catch (\Throwable $e) {
                $this->connection->rollBack();
                $output->writeln(sprintf('<error>Migration %s Error</error>', \get_class($this)));
                $output->writeln(sprintf('<error>%s</error>', $e));
            }

            if ($mode === self::DOWN_MODE) {
                $this->connection->executeQuery('DELETE FROM migrations m WHERE m.version = ?', [
                    $className
                ]);
            } elseif ($mode === self::UP_MODE) {
                $this->connection->executeQuery('INSERT INTO migrations(version) VALUES(?)', [
                    $className
                ], [
                    ParameterType::STRING
                ]);
            } else {
                throw new \RuntimeException('Invalid mode');
            }

            $executedMigrations++;
        }

        if (0 === $executedMigrations) {
            throw new \RuntimeException('Nothing to execute');
        }
    }

    private function getClassNameFromFile(File $file)
    {
        $filename = $file->getFilename();
        $pathInfo = pathinfo($filename);
        return $pathInfo['filename'];
    }
}