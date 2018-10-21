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
use Afw\Component\Templater\RendererInterface;
use Doctrine\DBAL\Connection;
use Psr\Http\Message\RequestInterface;

class Controller implements ControllerInterface
{
//region SECTION: Fields
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var RendererInterface
     */
    private $renderer;
//endregion Fields

//region SECTION: Constructor
    /**
     * Controller constructor.
     *
     * @param RendererInterface $renderer
     */
    public function __construct(
        RendererInterface $renderer
    ) {
        $this->renderer = $renderer;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function indexAction(RequestInterface $request)
    {
        return $this->renderer->render('base.html.twig');
    }
//endregion Public
}