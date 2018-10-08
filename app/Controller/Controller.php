<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:32
 */

namespace App\Controller;


use Afw\Component\Controller\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ControllerInterface
{
//region SECTION: Public
    public function indexAction($name, $age)
    {
        return new Response(sprintf('Hello %s, %s age old', $name, $age));
    }
//endregion Public
}