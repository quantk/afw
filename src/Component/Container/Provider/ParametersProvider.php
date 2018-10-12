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
            'template.path' => implode(DIRECTORY_SEPARATOR, [ROOT_DIR,'resources','templates']),
            'routes.path' => implode(DIRECTORY_SEPARATOR, [ROOT_DIR,'app','routes.php']),
            'cache.renderer.path' => implode(DIRECTORY_SEPARATOR, [ROOT_DIR,'var','cache','renderer']),
        ];
    }
}