<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 23/09/2015
 * Time: 09:57
 */

namespace Sofie\AdminBundle\Controller;


use Sofie\AdminBundle\Entity\Config;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionInfo
{
    static public function getPaginationOffset(SessionInterface $session)
    {
        $infos = $session->get(Config::SESSION_NAME, array());
        return (array_key_exists(Config::SESSNAME_OFFSET_PAGINATOR, $infos))
            ? $infos[Config::SESSNAME_OFFSET_PAGINATOR]
            : Config::DEFAULT_OFFSET_PAGINATOR
        ;
    }

    static public function getCarteRegionUrl(SessionInterface $session)
    {
        $infos = $session->get(Config::SESSION_NAME, array());
        return (array_key_exists(Config::SESSNAME_CARTE_REGION_URL, $infos))
            ? $infos[Config::SESSNAME_CARTE_REGION_URL]
            : null
        ;
    }

    static public function getCarteCentralUrl(SessionInterface $session)
    {
        $infos = $session->get(Config::SESSION_NAME, array());
        return (array_key_exists(Config::SESSNAME_CARTE_CENTRAL_URL, $infos))
            ? $infos[Config::SESSNAME_CARTE_CENTRAL_URL]
            : null
        ;
    }

    static public function getApiGetKeyUrl(SessionInterface $session)
    {
        $infos = $session->get(Config::SESSION_NAME, array());
        return (array_key_exists(Config::SESSNAME_API_GET_KEY_URL, $infos))
            ? $infos[Config::SESSNAME_API_GET_KEY_URL]
            : null
        ;
    }
}