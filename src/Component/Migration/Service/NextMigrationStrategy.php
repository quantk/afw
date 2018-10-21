<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:49
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Migration\Migration;
use Doctrine\DBAL\ParameterType;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

class NextMigrationStrategy extends AbstractMigrationStrategy implements MigrateStrategyInterface
{
    /**
     * @param array $versions
     * @return array
     */
    public function prepareVersions(array $versions): array
    {
        return $versions;
    }

    /**
     * @param array $versions
     * @param OutputInterface $output
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function execute(array $versions, OutputInterface $output): void
    {
        $executedMigrations = 0;
        $migrations = $this->getMigrations();
        foreach ($migrations as $migration) {
            $file = new File($this->getMigrationsPath() . DIRECTORY_SEPARATOR . $migration);
            $className = $this->getClassNameFromFile($file);

            if (false === $this->needToExecute($className, $versions)) {
                continue;
            }

            $migrationClass = "\\App\\Migrations\\${className}";
            /** @var Migration $migration */
            $migration = new $migrationClass($this->getConnection(), $output);

            $migration->up();
            $connection = $this->getConnection();
            $connection->beginTransaction();
            $migrationClass = \get_class($migration);
            try {
                $migration->execute();
                $connection->commit();
                $output->writeln(sprintf('<success>Migration %s successfully executed</success>', $migrationClass));
                $connection->executeQuery('INSERT INTO migrations(version) VALUES(?)', [
                    $this->getBaseMigrationClass($migration)
                ], [
                    ParameterType::STRING
                ]);
            } catch (\Throwable $e) {
                $connection->rollBack();
                $output->writeln(sprintf('<error>Migration %s Error</error>', $migrationClass));
                $output->writeln(sprintf('<error>%s</error>', $e));
            }

            $executedMigrations++;
        }

        if (0 === $executedMigrations) {
            throw new \RuntimeException('Nothing to execute');
        }
    }

    /**
     * @param string $migrationClass
     * @param array $versions
     * @return bool
     */
    private function needToExecute(string $migrationClass, array $versions): bool
    {
        if (in_array($migrationClass, $versions)) {
            return false;
        }

        return true;
    }
}