<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.11.2018
 * Time: 13:42
 */

return [
    'migration.path' => function (\Afw\Kernel $kernel) {
        return implode(DIRECTORY_SEPARATOR, [$kernel->getRootDir(), 'app', 'Migrations']);
    },
];