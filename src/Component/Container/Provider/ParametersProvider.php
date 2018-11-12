<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 12.10.18
 * Time: 18:41
 */

namespace Afw\Component\Container\Provider;


final class ParametersProvider implements ServiceProviderInterface
{

    public function getDefinitions(): array
    {
        return [
            'root.path' => getRootDir(),
            'config.path' => getConfigDir(),
            'template.path' => implode(DIRECTORY_SEPARATOR, [getRootDir(), 'resources', 'templates']),
            'routes.path' => implode(DIRECTORY_SEPARATOR, [getRootDir(), 'app', 'routes.php']),
            'cache.renderer.path' => implode(DIRECTORY_SEPARATOR, [getRootDir(), 'var', 'cache', 'renderer']),
        ];
    }
}