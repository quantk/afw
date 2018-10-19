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
     * @return MigrateStrategyInterface
     */
    public function create(Mode $mode, Connection $connection): MigrateStrategyInterface
    {
        if (Mode::UP_MODE === $mode->getMode()) {
            return new UpMigrateStrategy($mode, $connection);
        }

        if (Mode::DOWN_MODE === $mode->getMode()) {
            return new DownMigrateStrategy($mode, $connection);
        }

        throw new \RuntimeException('invalid mode');
    }
}