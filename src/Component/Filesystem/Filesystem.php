<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 11:45
 */

namespace Afw\Component\Filesystem;


class Filesystem
{
    public function getClassesFromDirectory(string $directory): array
    {
        $classes = [];
        foreach (glob(sprintf('%s/*.php', $directory)) as $file) {
            $classes[] = basename($file, '.php');
        }

        return $classes;
    }
}