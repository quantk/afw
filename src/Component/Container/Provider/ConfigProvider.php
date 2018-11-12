<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 12:54
 */

namespace Afw\Component\Container\Provider;


final class ConfigProvider implements ServiceProviderInterface
{
    public function getDefinitions(): array
    {
        $configDir = getConfigDir();
        $configFiles       = scandir($configDir, SCANDIR_SORT_NONE);
        $configDefinitions = [];
        foreach ($configFiles as $config) {
            if (
                $config === 'providers.php' ||
                $config === '.' ||
                $config === '..'
            ) {
                continue;
            }

            $configDefinitions[] = require_once implode(DIRECTORY_SEPARATOR, [$configDir, $config]);
        }

        return array_merge(...$configDefinitions);
    }
}