<?php

namespace Sofie\AdminBundle\Controller;

use Sofie\AdminBundle\Daemon\ConfigChangeEvent;
use Sofie\AdminBundle\Daemon\DaemonEvents;
use Sofie\AdminBundle\Entity\Config;
use Sofie\AdminBundle\Entity\DelaisNotification;
use Sofie\AdminBundle\Model\ConfigFile;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\Entity\StatutPanne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Url;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ParametreController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    public function indexAction(Request $request)
    {
//        var_dump(unserialize($_SESSION['_sf2_attributes']['_security_sofie_secure_area'])->getRoles());die;
        return $this->render('SofieAdminBundle:Parametre:index.html.twig', array(

            ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function appliConfigAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $result = array('success'=>false);
        $config = $em->getRepository('SofieAdminBundle:Config')->getCurrent();
        if($config === null){
            $config = new Config();
            $config->setRegion($em->getRepository('SofieExpBundle:Region')->find(intval(self::$site)));
            $custId = self::$site;
            if(is_null($custId) || $custId==0){
                $custId = 100;
            }
            $config->setId($custId);
        }
        $form = $this->createFormBuilder($config)
            ->add('offsetPaginator', 'integer', array(
                'required'=>true, 'trim'=>true, 'label'=>"Nombre d'éléments affichés par page",
                'constraints'=>array(
                    new NotBlank(),
                    new LessThanOrEqual(array('value'=>200)),
                    new GreaterThanOrEqual(array('value'=>1)),
                    new Type(array('type'=>'integer', 'message'=>"Cette valeur doit être un entier"))
                )
            ))
            ->add('nbPaginator', 'integer', array(
                'required'=>true, 'trim'=>true, 'label'=>"Nombre de paginations affichées",
                'constraints'=>array(
                    new NotBlank(),
                    new LessThanOrEqual(array('value'=>30)),
                    new GreaterThanOrEqual(array('value'=>1)),
                    new Type(array('type'=>'integer', 'message'=>"Cette valeur doit être un entier"))
                )
            ))
            ->add('statUrl', 'url', array(
                'required'=>false, 'trim'=>true, 'label'=>"Url de l'application des statistiques",
                'constraints'=>array(
                    new Url()
                )
            ))
            ->add('apiGetKeyUrl', 'url', array(
                'required'=>true, 'trim'=>true, 'label'=>"Url du générateur de clés",
                'constraints'=>array(
                    new NotBlank(),
                    new Url()
                )
            ))
            ->add('carteCentralUrl', 'url', array(
                'required'=>false, 'trim'=>true, 'label'=>"Url de la carte centrale",
                'constraints'=>array(
                    new Url()
                )
            ))
            ->add('carteRegionUrl', 'url', array(
                'required'=>false, 'trim'=>true, 'label'=>"Url de la carte régionale",
                'constraints'=>array(
                    new Url()
                )
            ))
            ->getForm()
        ;
        if($request->getMethod() == Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->persist($config);
                $em->flush();
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètres de configuration modifiés';
                Logging::write($logMsg);
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètres de configuration écrits dans la session ';
                $sessionEvent  = new ConfigChangeEvent($logMsg, $config);
                $this->get('event_dispatcher')->dispatch(DaemonEvents::CONFIG_CHANGE, $sessionEvent);
                if(!$request->isXmlHttpRequest()){
                    $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                    return $this->redirectToRoute('sofieadmin_param_index');
                }
                $result['success'] = true;
            }
            if($request->isXmlHttpRequest()){
                $result['form'] = $this->renderView('SofieAdminBundle:Parametre:include/appli_config.html.twig', array(
                    'form' => $form->createView()
                ));
                return new Response(json_encode($result, JSON_PRETTY_PRINT));
            }
        }

        return $this->render('SofieAdminBundle:Parametre:include/appli_config.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function regionsConfigAction(Request $request)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $result = array('success'=>false);
        $configs = $em->getRepository('SofieAdminBundle:Config')->getRegionaux();
        if($request->getMethod() == Request::METHOD_POST){
            $configsId = $request->request->get('configs');
            $logMsg = '['.$this->getUser()->getUsername().'] ';
            Logging::write($logMsg." Début de la mise à jour des numéros des sites");
            foreach($configs as $config){
                if(method_exists($config, 'setPhoneNumber') && method_exists($config, 'getId')){
                    if(array_key_exists($config->getId(), $configsId)){
                        $site = ($config->getRegion() !== null) ? 'Région '.$config->getRegion()->getNom() : 'Site central';
                        Logging::write($logMsg.' '.$site.' mise à jour - infos[Ancien:'
                            .$config->getPhoneNumber().', Nouveau:'.$configsId[$config->getId()]);
                        $config->setPhoneNumber($configsId[$config->getId()]);
                    }
                }
            }
            $em->flush();
            Logging::write($logMsg." Fin de la mise à jour des numéros des sites.");
            if(!$request->isXmlHttpRequest()){
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirectToRoute('sofieadmin_param_index');
            }
            $result['success'] = true;
            if($request->isXmlHttpRequest()){
                /*$result['form'] = $this->renderView('SofieAdminBundle:Parametre:include/regions_config.html.twig', array(
                    'configs' => $configs
                ));*/
                return new Response(json_encode($result, JSON_PRETTY_PRINT));
            }
        }


        return $this->render('SofieAdminBundle:Parametre:include/regions_config.html.twig', array(
            'configs' => $configs
        ));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function regionsModemConfigAction(Request $request)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $result = array('success'=>false);
        $regions = $em->getRepository('SofieExpBundle:Region')->getFullAll();
        if($request->getMethod() == Request::METHOD_POST){
            $regionsId = $request->request->get('regions', array());
            $logMsg = '['.$this->getUser()->getUsername().'] ';
            Logging::write($logMsg." Début de la mise à jour des numéros de modem des régions.");
            foreach($regions as $region){
                if(method_exists($region, 'setNumeroModem') && method_exists($region, 'getId')){
                    if(array_key_exists($region->getId(), $regionsId)){
                        Logging::write($logMsg.' '.$region->getNom().' mise à jour - infos[Ancien:'
                            .$region->getNumeroModem().', Nouveau:'.$regionsId[$region->getId()]);
                        $region->setNumeroModem($regionsId[$region->getId()]);
                    }
                }
            }
            $em->flush();
            Logging::write($logMsg." Fin de la mise à jour des numéros de modem des régions.");
            if(!$request->isXmlHttpRequest()){
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirectToRoute('sofieadmin_param_index');
            }
            $result['success'] = true;
            if($request->isXmlHttpRequest()){
                /*$result['form'] = $this->renderView('SofieAdminBundle:Parametre:include/regions_config.html.twig', array(
                    'configs' => $configs
                ));*/
                return new Response(json_encode($result, JSON_PRETTY_PRINT));
            }
        }


        return $this->render('SofieAdminBundle:Parametre:include/regions_modem_config.html.twig', array(
            'regions' => $regions
        ));
    }

    /**
     * @Security("has_role('ROLE_DELAIS_NOTIF_SETTING')")
     */
    public function editDelaisNotificationAction(Request $request)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $delaisNotification = $em->getRepository('SofieAdminBundle:DelaisNotification')->getLast();
        if(!is_object($delaisNotification)){
            $delaisNotification = new DelaisNotification();
        }

        $form = $this->createFormBuilder($delaisNotification)
                ->add('horsPriseCommande', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Hors délais de prise de commande'))
                ->add('horsReparation', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Hors délais de réparation'))
                ->add('uniteParDefaut', 'text', array(
                    'required'=>false, 'trim'=>true, 'label'=>'Unité par défaut des délais',
                    'read_only'=>true
                ))
                ->add('uniteDelais', 'entity', array(
                    'required'=>false, 'trim'=>true, 'label'=>'Unité des délais',
                    'class' => 'SofieAdminBundle:UniteDelais',
                    'placeholder' => '---',
                    'empty_data' => null
                ))
            ->getForm()
        ;

        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($request->isXmlHttpRequest()){
                $em->persist($delaisNotification);
                $em->flush();
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètre DelaisNotification modifié';
                if(method_exists($delaisNotification, 'logString')){
                    $logMsg .= ' - infos ['.$delaisNotification->logString().']';
                }
                Logging::write($logMsg);
                return new Response(json_encode(array('success'=>true), JSON_PRETTY_PRINT));
            }
        }

        return $this->render('SofieAdminBundle:Parametre:include/delaisNotification.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_COULEUR_PANNE_SETTING')")
     */
    public function editCouleurPanneAction(Request $request)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $statutPannes = $em->getRepository('SofieExpBundle:StatutPanne')->findAll();
        $couleurPannes = $em->getRepository('SofieAdminBundle:CouleurPanne')->findAll();

        if($request->isXmlHttpRequest()){
            if($request->getMethod() == Request::METHOD_POST){
                $couleurs = $request->request->get('couleurs', array());
                $statut = null;
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètre CouleurPanne modifié';
                $logMsg .= ' - infos [';
                $couleurList = '';
                foreach($couleurs as $key => $value){
                    $statut = $em->getRepository('SofieExpBundle:StatutPanne')->find($key);
                    if($statut && $statut instanceof StatutPanne){
                        $statut->setIcone($value);
                    }
                    if(!empty($couleurList)) $couleurList .= ', ';
                    $couleurList .= $statut->getLibelle().' : '.$statut->getIcone();
                }
                $em->flush();
                $logMsg .= $couleurList.']';
                Logging::write($logMsg);
                return new Response(json_encode(array('success'=>true), JSON_PRETTY_PRINT));
            }
        }

        return $this->render('SofieAdminBundle:Parametre:include/couleurPanne.html.twig', array(
            'statutPannes' => $statutPannes,
            'couleurPannes' => $couleurPannes
        ));
    }

    /**
     * @Security("has_role('ROLE_SMS_SETTING')")
     */
    public function smsConfigAction(Request $request)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $result = array('success'=>false);
        $config = $em->getRepository('SofieAdminBundle:Config')->getCurrent();
        if($config === null){
            $config = new Config();
            $config->setRegion($em->getRepository('SofieExpBundle:Region')->get(intval(self::$site)));
            $custId = self::$site;
            if(is_null($custId) || $custId==0){
                $custId = 100;
            }
            $config->setId($custId);
        }
        $form = $this->createFormBuilder($config)
            ->add('smsSoa', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>"Numéro de l'émetteur",
                'constraints'=>array(
                    new Regex(array(
                        'pattern'=>'/^(\d|\+)\d{7,14}$/',
                        'message'=>'Numéro non valide.'
                    ))
                )
            ))
            ->add('smsGw', 'url', array(
                'required'=>true, 'trim'=>true, 'label'=>"Adresse de la passerelle",
                'constraints'=>array(
                    new NotBlank(),
                    new Url()
                )
            ))
            ->getForm()
        ;

        if($request->getMethod() == Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->persist($config);
                $em->flush();
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètre SmsConfig modifié';
                $logMsg .= ' - infos [gateway : '.$config->getSmsGw().', soa : '.$config->getSmsSoa().']';
                Logging::write($logMsg);
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètres de configuration écrits dans la session ';
                $sessionEvent  = new ConfigChangeEvent($logMsg, $config);
                $this->get('event_dispatcher')->dispatch(DaemonEvents::CONFIG_CHANGE, $sessionEvent);
                if(!$request->isXmlHttpRequest()){
                    $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                    return $this->redirectToRoute('sofieadmin_param_index');
                }
                $result['success'] = true;
            }
            if($request->isXmlHttpRequest()){
                $result['form'] = $this->renderView('SofieAdminBundle:Parametre:include/sms_config.html.twig', array(
                    'form' => $form->createView()
                ));
                return new Response(json_encode($result, JSON_PRETTY_PRINT));
            }

            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
            return $this->redirectToRoute('sofieadmin_param_index');
        }

        return $this->render('SofieAdminBundle:Parametre:include/sms_config.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAddModeAction(Request $request)
    {
        $addMode = ConfigFile::loadAddMode();
        $form = $this->createFormBuilder()
            ->add('mode', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Choisissez un mode d\'ajout',
                'choices'=>array(
                    ConfigFile::ADD_GET_KEY=>'Obtention de clé',
//                    ConfigFile::ADD_GET_ENTITY=>'Création à distance'
                ),
                'placeholder'=>null,
                'empty_data'=>null
            ))
            ->getForm()
        ;
        if(array_key_exists('mode', $addMode)){
            $form->get('mode')->setData($addMode['mode']);
        }

        if($request->getMethod() == Request::METHOD_POST){
            $form->handleRequest($request);
            $addMode['mode'] = $form->get('mode')->getData();
            ConfigFile::saveAddMode($addMode);
            $logMsg = '['.$this->getUser()->getUsername().'] Paramètre AddMode modifié';
            $logMsg .= ' - infos [mode : '.$addMode['mode'].']';
            Logging::write($logMsg);
            if($request->isXmlHttpRequest()){
                return new Response(json_encode(array('success'=>true), JSON_PRETTY_PRINT));
            }

            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
            return $this->redirectToRoute('sofieadmin_param_index');
        }

        return $this->render('SofieAdminBundle:Parametre:include/add_mode.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_SMS_SETTING')")
     */
    public function smsContentConfigAction(Request $request){
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $table = 't_sms_content';
        $con = $this->getDoctrine()->getConnection();
        $sth = $con->prepare("SELECT * FROM `{$table}`");
        $sth->execute();
        $smsContents = $sth->fetchAll();

        if($request->getMethod() == Request::METHOD_POST){
            $contents = $request->request->all();
            if(is_array($contents) && array_key_exists('da', $contents) && is_array($contents['da'])){
                foreach($contents['da'] as $key=>$value){
                    $sth = $con->prepare("
                        UPDATE `{$table}` SET `DA` = :da, `SMS_CONTENT` = :contenu, `DESCRIPTION` = :description
                        WHERE `ID` = :id
                    ");
                    $sth->bindParam(':da', $contents['da'][$key], \PDO::PARAM_STR);
                    $sth->bindParam(':contenu', $contents['contenu'][$key], \PDO::PARAM_STR);
                    $sth->bindParam(':description', $contents['description'][$key], \PDO::PARAM_STR);
                    $sth->bindParam(':id', $key, \PDO::PARAM_INT);
                    $sth->execute();
                }
                $logMsg = '['.$this->getUser()->getUsername().'] Paramètre SMSContentConfig modifié';
                Logging::write($logMsg);
            }
            if($request->isXmlHttpRequest()){
                return new Response(json_encode(array('success'=>true), JSON_PRETTY_PRINT));
            }
            $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
            return $this->redirectToRoute('sofieadmin_param_index');
        }

        return $this->render('SofieAdminBundle:Parametre:include/sms_content_config.html.twig', array(
            'smsContents' => $smsContents
        ));
    }
}
