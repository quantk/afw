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

class PrevMigrationStrategy extends AbstractMigrationStrategy implements MigrateStrategyInterface
{
    /**
     * @param array $versions
     * @return array
     */
    public function prepareVersions(array $versions): array
    {
        return array_reverse($versions);
    }

    /**
     * @param array $versions
     * @param OutputInterface $output
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws \ReflectionException
     */
    public function execute(array $versions, OutputInterface $output): void
    {
        if (empty($versions)) {
            throw new \RuntimeException('Nothing to execute');
        }

        $prevMigrationVersion = \reset($versions);

        $migrationClass = "\\App\\Migrations\\{$prevMigrationVersion}";

        /** @var Migration $migration */
        $migration = $this->getReflector()->initialize($migrationClass, [$this->getConnection()]);

        $migration->down();
        $connection = $this->getConnection();
        $connection->beginTransaction();
        $migrationClass = \get_class($migration);
        try {
            $migration->execute();
            $connection->commit();
            $connection->executeQuery('DELETE FROM migrations m WHERE m.version = ?', [
                $this->getBaseMigrationClass($migration)
            ], [
                ParameterType::STRING
            ]);
            $output->writeln(sprintf('<success>Migration %s successfully rollbacked</success>', $migrationClass));
        } catch (\Throwable $e) {
            $connection->rollBack();
            $output->writeln(sprintf('<error>Migration %s Error</error>', $migrationClass));
            $output->writeln(sprintf('<error>%s</error>', $e));
        }
    }
}