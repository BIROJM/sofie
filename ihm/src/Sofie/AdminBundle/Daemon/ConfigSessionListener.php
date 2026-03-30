<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 21/09/2015
 * Time: 15:31
 */

namespace Sofie\AdminBundle\Daemon;


use Sofie\AdminBundle\Entity\Config;
use Sofie\AdminBundle\Model\Logging;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ConfigSessionListener implements EventSubscriberInterface
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * ConfigSessionListener constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    static public function getSubscribedEvents(){
        return array(
            'sofie.daemon.config_change' => 'writeSession'
        );
    }

    public function writeSession(ConfigChangeEvent $event)
    {
        if($event->getConfig() !== null){
            $this->session->set(Config::SESSION_NAME, $event->getConfig()->sessionArray());
            Logging::write($event->getMessage());
        }
    }
}