<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 19.10.2018
 * Time: 9:48
 */

namespace Afw\Component\Migration\Service;


use Symfony\Component\Console\Output\OutputInterface;

interface MigrateStrategyInterface
{
    public function prepareVersions(array $versions): array;

    public function execute(array $versions, OutputInterface $output): void;
}