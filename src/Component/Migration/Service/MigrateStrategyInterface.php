<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:48
 */

namespace Afw\Component\Migration\Service;


use Afw\Component\Migration\Migration;
use Afw\Component\Migration\Mode;
use Symfony\Component\Console\Output\OutputInterface;

interface MigrateStrategyInterface
{
    public function getMode(): Mode;

    public function prepareVersions(array $versions): array;

    public function needToExecute(string $migrationClass, array $versions): bool;

    public function execute(Migration $migration, OutputInterface $output): void;
}