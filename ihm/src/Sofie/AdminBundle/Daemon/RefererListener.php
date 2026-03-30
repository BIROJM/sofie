<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 21/09/2015
 * Time: 15:31
 */

namespace Sofie\AdminBundle\Daemon;



use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

class RefererListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $refererSessname;


    /**
     * RefererListener constructor.
     * @param string $refererSessname
     */
    public function __construct($refererSessname)
    {
        $this->refererSessname = $refererSessname;
    }

    static public function getSubscribedEvents(){
        return array(
            'kernel.request' => 'writeReferer'
        );
    }

    public function writeReferer(GetResponseEvent $event)
    {
        if(!$event->isMasterRequest()) return;
        $request = $event->getRequest();
        if($request->isMethod(Request::METHOD_GET) && !$request->isXmlHttpRequest()){
            $referer = $request->server->get('HTTP_REFERER', null);
            $refererSess = $request->getSession()->get($this->refererSessname, array());
            if(!is_array($refererSess)) $refererSess = array();
            $uri = $request->getUri();
            $requestUri = $request->getRequestUri();
            if(!array_key_exists($requestUri, $refererSess)) $refererSess[$requestUri] = null;
            if(!is_null($referer) && $referer!=$refererSess[$requestUri] && $referer!=$uri && $refererSess[$requestUri]!=$uri){
                $refererSess[$requestUri] = $referer;
                $rlen = count($refererSess);
                if($rlen > 8){
                    $refererSess = array_slice($refererSess, -10, null, true);
                }
                $request->getSession()->set($this->refererSessname, $refererSess);
            }
        }
    }
}