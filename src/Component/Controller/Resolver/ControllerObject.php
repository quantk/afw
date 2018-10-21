<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 21:44
 */

namespace Afw\Component\Controller\Resolver;


use Afw\Component\Controller\ControllerInterface;

class ControllerObject
{
//region SECTION: Fields
    /**
     * @var ControllerInterface
     */
    private $controller;
    /**
     * @var string
     */
    private $action;
//endregion Fields

//region SECTION: Constructor
    /**
     * ControllerObject constructor.
     *
     * @param ControllerInterface $controller
     * @param string $action
     */
    public function __construct(ControllerInterface $controller, string $action)
    {
        $this->controller = $controller;
        $this->action     = $action;
    }
//endregion Constructor

//region SECTION: Getters/Setters
    /**
     * @return ControllerInterface
     */
    public function getController(): ControllerInterface
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
//endregion Getters/Setters
}