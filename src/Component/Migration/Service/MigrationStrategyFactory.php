<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:50
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Filesystem\Filesystem;
use Afw\Component\Migration\Mode;
use Afw\Component\Reflection\Reflector;
use Doctrine\DBAL\Connection;

final class MigrationStrategyFactory
{
    /**
     * @param Mode $mode
     * @param Connection $connection
     * @param Filesystem $filesystem
     * @param Reflector $reflector
     * @param String $migrationsPath
     * @param string $migrationsNamespace
     * @return MigrateStrategyInterface
     */
    public function create(
        Mode $mode,
        Connection $connection,
        Filesystem $filesystem,
        Reflector $reflector,
        string $migrationsPath,
        string $migrationsNamespace
    ): MigrateStrategyInterface
    {
        if (Mode::NEXT_MODE === $mode->getMode()) {
            return new NextMigrationStrategy($mode, $connection, $filesystem, $reflector, $migrationsPath, $migrationsNamespace);
        }

        if (Mode::PREV_MODE === $mode->getMode()) {
            return new PrevMigrationStrategy($mode, $connection, $filesystem, $reflector, $migrationsPath, $migrationsNamespace);
        }

        throw new \RuntimeException('invalid mode');
    }
}