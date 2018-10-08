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
use DI\Annotation\Inject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ControllerInterface
{
//region SECTION: Public
    /**
     * @var string
     */
    private $dbHost;

    /**
     * Controller constructor.
     * @Inject({"db.host"})
     * @param string $dbHost
     */
    public function __construct(
        string $dbHost
    )
    {
        $this->dbHost = $dbHost;
    }


    /**
     * @param         $name
     * @param         $age
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction($name, $age, Request $request)
    {
        return new Response(sprintf('Hello %s, %s age old', $name, $age));
    }
//endregion Public
}