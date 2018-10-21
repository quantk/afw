<?php
declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 08.10.18
 * Time: 21:40
 */

namespace Afw\Component\Controller\Resolver;


class RouteParameters
{
//region SECTION: Fields
    private $controller;
    private $controllerMethod;
    private $allParameters;
//endregion Fields

//region SECTION: Constructor
    /**
     * RouteParameters constructor.
     *
     * @param array $params
     */
    public function __construct(array $params = [])
    {
        $this->controller       = $params['_controller'];
        $this->controllerMethod = $params['_action'];
        $this->allParameters    = $params;
    }
//endregion Constructor

//region SECTION: Getters/Setters
    /**
     * @param $paramKey
     *
     * @return mixed|null
     */
    public function get($paramKey)
    {
        return $this->allParameters[$paramKey] ?? null;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getControllerMethod()
    {
        return $this->controllerMethod;
    }

    /**
     * @return mixed
     */
    public function getAllParameters()
    {
        return $this->allParameters;
    }
//endregion Getters/Setters
}