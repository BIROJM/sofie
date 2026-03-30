<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 02/07/2015
 * Time: 14:24
 */

namespace Sofie\ExpBundle\Form\EventListener;

use Doctrine\ORM\EntityManager;
use Sofie\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AgentSubscriber implements EventSubscriberInterface
{
    private $site;
    private $region;
    private $em;

    public function __construct(EntityManager $em, $site)
    {
        $this->em = $em;
        $this->site = $site;
        $this->region = $this->em->getRepository('SofieExpBundle:Region')->getOneByUserRegion();
    }

    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SET_DATA => 'preSetData', FormEvents::POST_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        if(!is_null($this->site) && is_null($data) && method_exists($data, 'getRegion')){
            if(is_null($data->getRegion())){
                $data->setRegion($this->region);
            }
        }
    }

}