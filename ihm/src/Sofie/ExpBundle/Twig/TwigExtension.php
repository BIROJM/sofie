<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 20/07/2015
 * Time: 13:32
 */

namespace Sofie\ExpBundle\Twig;


use Sofie\AdminBundle\Entity\Config;
use Sofie\UserBundle\Entity\Droit;
use Sofie\UserBundle\Entity\Groupe;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var Request
     */
    protected $request;

    /**
     * TwigExtension constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
        if($this->request instanceof Request){
            $this->session = $requestStack->getCurrentRequest()->getSession();
        }
    }

    /**
     * Variables globale
     *
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'sofie_config' => $this->session->get(Config::SESSION_NAME, null),
        );
    }

    /**
     * Les fonctions
     *
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('groupe_granted', array($this, 'groupeGranted')),
            new \Twig_SimpleFunction('base_project_url', array($this, 'baseProjectUrl')),
        );
    }

    public function groupeGranted(Groupe $groupe = null, Droit $droit = null)
    {
        $granted = false;
        if(!is_null($groupe) && !is_null($droit)){
            foreach($groupe->getGroupeDroits() as $groupeDroit){
                if($groupeDroit->getDroit()){
                    if($groupeDroit->getDroit()->getId() == $droit->getId()){
                        $granted = true;
                        break;
                    }/*else{
                        foreach($droit->getChildren() as $child){
                            return $this->groupeGranted($groupe, $child);
                        }
                    }*/
                }
            }
        }
        return $granted;
    }

    public function baseProjectUrl()
    {
        $baseAppHost = preg_replace('/(\/+)$/', '/', $this->request->getSchemeAndHttpHost());
        $basePath = $this->request->getBasePath();
        if(strpos($basePath, '/', 1) !== false){
            $basePath = substr($basePath, 1, strpos($basePath, '/', 1)-1);
        }else{
            $basePath = substr($basePath, 1);
        }
        return preg_replace('/(\/+)$/', '/', $baseAppHost.'/'.$basePath);
    }


    /**
     * Les Filtres
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'check_true' => new \Twig_Filter_Method($this, 'checkTrue'),
            'check_false' => new \Twig_Filter_Method($this, 'checkFalse'),
            'check_both' => new \Twig_Filter_Method($this, 'checkBoth'),
            'check_init' => new \Twig_Filter_Method($this, 'checkInit'),
            'oui_non' => new \Twig_Filter_Method($this, 'ouiNon'),
            'bon_mauvais' => new \Twig_Filter_Method($this, 'bonMauvais'),
            'bonne_mauvaise' => new \Twig_Filter_Method($this, 'bonneMauvaise'),
            'origine' => new \Twig_Filter_Method($this, 'origine'),
            'admin' => new \Twig_Filter_Method($this, 'admin'),
            'mobile' => new \Twig_Filter_Method($this, 'mobile'),
            'inactive' => new \Twig_Filter_Method($this, 'inactive')
        );
    }

    public function checkTrue($state)
    {
        return $state ? '<i class="fa fa-check text-success"></i>' : '';
    }

    public function checkFalse($state)
    {
        return $state ? '' : '<i class="fa fa-check text-danger"></i>';
    }

    public function checkBoth($state)
    {
        return $state ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-check text-danger"></i>';
    }

    public function checkInit($state)
    {
        return $state ? '<i class="fa fa-check text-success" title="Initialisé"></i>' : '<i class="fa fa-check text-danger" title="Non initialisé"></i>';
    }

    public function ouiNon($state)
    {
        $result='';
        if($state===true) $result='Oui';
        if($state===false) $result='Non';
        return $result;
    }

    public function bonMauvais($state)
    {
        $result='';
        if($state===true) $result='Bon';
        if($state===false) $result='Mauvais';
        return $result;
    }

    public function bonneMauvaise($state)
    {
        $result='';
        if($state===true) $result='Bonne';
        if($state===false) $result='Mauvaise';
        return $result;
    }

    public function origine($state)
    {
        return strtoupper($state)=='M' ? '<i class="glyphicon glyphicon-phone" title="Mobile"></i>' : '<i class="fa fa-laptop" title="IHM"></i>';
    }

    public function admin($state)
    {
        return $state ? '<i class="fa fa-genderless text-primary" title="Administrateur"></i>' : '';
    }

    public function mobile($state)
    {
        return $state ? '<i class="fa fa-mobile text-info" title="Utilisateur mobile"></i>' : '';
    }

    public function inactive($state)
    {
        return !$state ? '<i class="fa fa-lock text-danger" title="Compte inactif"></i>' : '';
    }


    public function getName()
    {
        return 'sofie_twig_extension';
    }
}