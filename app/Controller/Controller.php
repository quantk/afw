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
use App\Service\ServiceInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ControllerInterface
{
//region SECTION: Fields
    /**
     * @var Connection
     */
    private $connection;
//endregion Fields

//region SECTION: Constructor
    /**
     * Controller constructor.
     *
     * @param Connection $connection
     */
    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }
//endregion Constructor

//region SECTION: Public
    /**
     * @param Request          $request
     *
     * @param ServiceInterface $service
     *
     * @return Response
     */
    public function indexAction(Request $request, ServiceInterface $service)
    {
        $users = $this->connection->fetchAll('SELECT * FROM users');

        return new Response(sprintf('Hello. Available users: <pre>%s</pre>', json_encode($users, JSON_PRETTY_PRINT)));
    }
//endregion Public
}