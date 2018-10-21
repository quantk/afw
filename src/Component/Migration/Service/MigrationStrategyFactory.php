<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:50
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Migration\Mode;
use Doctrine\DBAL\Connection;

final class MigrationStrategyFactory
{
    /**
     * @param Mode $mode
     * @param Connection $connection
     * @param String $migrationsPath
     * @return MigrateStrategyInterface
     */
    public function create(
        Mode $mode,
        Connection $connection,
        String $migrationsPath
    ): MigrateStrategyInterface
    {
        if (Mode::NEXT_MODE === $mode->getMode()) {
            return new NextMigrationStrategy($mode, $connection, $migrationsPath);
        }

        if (Mode::PREV_MODE === $mode->getMode()) {
            return new PrevMigrationStrategy($mode, $connection, $migrationsPath);
        }

        throw new \RuntimeException('invalid mode');
    }
}