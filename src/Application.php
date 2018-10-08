<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 19:09
 */

namespace Afw;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    /**
     * @var Request
     */
    private $request;

    /**
     * Application constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function run()
    {
        return (new Response('hello, world'))->send();
    }
}