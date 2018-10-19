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

class UpMigrateStrategy extends AbstractMigrationStrategy implements MigrateStrategyInterface
{


    public function do(): void
    {

    }


    public function prepareVersions(array $versions): array
    {
        return $versions;
    }

    public function needToExecute(string $migrationClass, array $versions): bool
    {
        if (in_array($migrationClass, $versions)) {
            return false;
        }

        return true;
    }

    /**
     * @param Migration $migration
     * @param OutputInterface $output
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \Doctrine\DBAL\DBALException
     * @throws \ReflectionException
     */
    public function execute(Migration $migration, OutputInterface $output): void
    {
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
    }
}