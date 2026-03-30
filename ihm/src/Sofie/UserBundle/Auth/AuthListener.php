<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 14/09/2015
 * Time: 11:41
 */

namespace Sofie\UserBundle\Auth;


use Sofie\AdminBundle\Daemon\ConfigChangeEvent;
use Sofie\AdminBundle\Entity\Config;
use Sofie\AdminBundle\Daemon\DaemonEvents;
use Sofie\AdminBundle\Model\Logging;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Event\AuthenticationEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Doctrine\ORM\EntityManagerInterface;

class AuthListener
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var integer
     */
    protected  $site;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * LoggerListener constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManagerInterface $em,RequestStack $requestStack, $site = null)
    {
        $this->dispatcher = $dispatcher;
        $this->em = $em;
        $this->site = $site;
        $this->requestStack = $requestStack;
    }


    public function success(AuthenticationEvent $event)
    {
        if(isset($_POST['_s2_auth_action'])){
            $token = $event->getAuthenticationToken();
            $logMsg = '['.$token->getUsername().'] Authentification réussite ';
            Logging::write($logMsg);
            $config = $this->em->getRepository('SofieAdminBundle:Config')->getCurrent();
            if(is_null($config)){
                $config = new Config();
                $config->setRegion($this->em->getRepository('SofieExpBundle:Region')->find(intval($this->site)));
            }
            $logMsg = '['.$token->getUsername().'] Paramètres de configuration écrits dans la session ';
            $sessionEvent  = new ConfigChangeEvent($logMsg, $config);
            $this->dispatcher->dispatch(DaemonEvents::CONFIG_CHANGE, $sessionEvent);
            $this->requestStack->getCurrentRequest()->getSession()
                ->set('sofie_auth_user', array(
                    'id'=>(!is_null($token->getUser()) && method_exists($token->getUser(), 'getId')) ? $token->getUser()->getId() : null,
                    'username'=>$token->getUsername(),
                    'roles'=>$this->getUserRoles($token->getRoles()),
                ));
            $logMsg = '['.$token->getUsername().'] Paramètres utilisateur écrits dans la session ';
            Logging::write($logMsg);
        }
    }

    public function failure(AuthenticationFailureEvent $event)
    {
        if(isset($_POST['_s2_auth_action'])) {
            $exception = $event->getAuthenticationException();
            $token = $event->getAuthenticationToken();
            $logMsg = ' Authentification échouée ';
            $logMsg .= ' - infos [username : ' . $token->getUsername() . ', message : ' . $exception->getMessage() . ']';
            Logging::write($logMsg);
        }
    }

    protected function getUserRoles(array $roles = array())
    {
        $result = array();
        foreach($roles as $role){
            if(is_object($role) && method_exists($role, 'getRole')){
                $result[] = $role->getRole();
            }
        }
        return $result;
    }
}