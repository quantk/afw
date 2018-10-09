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
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Controller implements ControllerInterface
{
//region SECTION: Public
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Controller constructor.
     *
     * @param Connection $connection
     */
    public function __construct(
        Connection $connection
    )
    {
        $this->connection = $connection;
    }


    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $users = $this->connection->fetchAll("SELECT * FROM users");

        return new Response(sprintf('Hello. Available users: <pre>%s</pre>', json_encode($users, JSON_PRETTY_PRINT)));
    }
//endregion Public
}