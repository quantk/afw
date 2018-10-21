<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 21:56
 */


/**
 * @param \Psr\Http\Message\ResponseInterface $response
 */
function sendResponse(\Psr\Http\Message\ResponseInterface $response)
{
    if (headers_sent()) {
        throw new \RuntimeException('Headers were already sent!');
    }

    $statusLine = sprintf('HTTP/%s %s %s'
        , $response->getProtocolVersion()
        , $response->getStatusCode()
        , $response->getReasonPhrase()
    );
    header($statusLine, TRUE);

    foreach ($response->getHeaders() as $name => $values) {
        $responseHeader = sprintf('%s: %s'
            , $name
            , $response->getHeaderLine($name)
        );
        header($responseHeader, FALSE);
    }

    echo $response->getBody();
    exit();
}
