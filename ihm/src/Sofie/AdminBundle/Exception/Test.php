<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 14/09/2015
 * Time: 11:08
 */

namespace Sofie\AdminBundle\Exception;


use Symfony\Component\HttpFoundation\Response;

class Test
{
    public function displayMsg(Response $response, $msg)
    {
        $response->setContent("J'ai remplacé le message en cours de route par : ".$msg);
        return $response;
    }
}