<?php

namespace Sofie\ExpBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Entity\Config;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\Entity\Region;
use Sofie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class DiffusionSMSController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * @Security("has_role('ROLE_SMS')")
     */
    public function indexAction(Request $request)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $form = $this->critereForm(
            $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Region')
                ->find(intval(self::$site))
        );
        if($request->getMethod() == Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $data = $this->critereData($form, $request);
                $regions = (array_key_exists('regions', $data)) ? $data['regions'] : array();
                $localites = (array_key_exists('localites', $data)) ? $data['localites'] : array();
                $comites = (array_key_exists('comites', $data)) ? $data['comites'] : array();
                $reparateurs = (array_key_exists('reparateurs', $data)) ? $data['reparateurs'] : array();
                $agents = (array_key_exists('agents', $data)) ? $data['agents'] : array();
                $autres = (array_key_exists('autres', $data)) ? $data['autres'] : array();
                $message = (array_key_exists('message', $data)) ? $data['message'] : '';

                $numbersArray = $this->numbersArray(array(
                    'regions' => $regions,
                    'localites' => $localites,
                    'comites' => $comites,
                    'reparateurs' => $reparateurs,
                    'agents' => $agents
                ));
                $result = $this->sendSMS(array_unique(array_merge($numbersArray, $autres)), $message);
                if($result !== false){
                    $genre = ($result > 1) ? 's' : '';
                    $this->get('ras_flash_alert.alert_reporter')
                        ->addSuccess('<i class="fa fa-check"></i> '.$result." message".$genre." envoyé".$genre." !")
                    ;
                    return $this->redirect($request->getRequestUri());
                }
            }
        }
        return $this->render('SofieExpBundle:DiffusionSMS:index.html.twig', array(
            'form' => ($form)?$form->createView():null
        ));
    }

    protected function sendSMS(array $numbers = array(), $msg = '')
    {
        $countSMS = 0;
        $smsParams = $this->get('session')->get(Config::SESSION_NAME, array(Config::SESSNAME_SMS_GW=>Config::DEFAULT_SMS_GW));
        if(array_key_exists(Config::SESSNAME_SMS_GW, $smsParams) && array_key_exists(Config::SESSNAME_SMS_SOA, $smsParams)){
            if($this->get('check_url')->check($smsParams['sms_gw'])){
                try{
                    foreach($numbers as $number){
                        file_get_contents(
                            strval($smsParams[Config::SESSNAME_SMS_GW]).'?SOA='.strval($smsParams[Config::SESSNAME_SMS_SOA]).'&DA='.$number.'&Modem=1&Content='.urlencode($msg)
                        );
                        $countSMS++;
                    }
                }catch (\Exception $e){
                    $this->get('ras_flash_alert.alert_reporter')
                        ->addError('<i class="fa fa-exclamation-circle"></i> Problème survenu lors de l\'interrogation de la parserelle.<br /> Veillez contacter votre administrateur !')
                    ;
                }
            }else{
                $this->get('ras_flash_alert.alert_reporter')
                    ->addWarning('<i class="fa fa-warning"></i> '
                        .'Impossible de contacter la gateway. Veillez contacter votre administrateur.'
                    )
                ;
                return false;
            }
        }else{
            $this->get('ras_flash_alert.alert_reporter')
                ->addWarning('<i class="fa fa-warning"></i> '
                    .'La parelle et/ou l\'adresse de l\'émetteur ne sont pas correctement configurées. '
                    .'Veillez contacter votre administrateur.'
                )
            ;
            return false;
        }
        return $countSMS;
    }

    protected function critereData(FormInterface $form, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $isAll = false;
        $regions = array();
        $localites = array();
        $comites = array();
        $reparateurs = array();
        $agents = array();
        $sociologues = array();
        $directeurs = array();
        $autres = array();
        $message = '';

//        Régions
        if($request->request->has('check_all_regions')){
            $isAll = true;
            $regions = $em->getRepository('SofieExpBundle:Region')->getFullAll();
        }else{
            if($form->has('regions')){
                $regions = $form->get('regions')->getData();
                if($regions instanceof ArrayCollection){
                    $regions = $regions->toArray();
                }
            }
        }
        if(!is_array($regions)){
            $regions = array();
        }

//        Localités
        if(!$isAll){
            if($request->request->has('check_all_localites')){
                $isAll = true;
                $localites = $em->getRepository('SofieExpBundle:Localite')->getFullAll();
            }else{
                if($form->has('localites')){
                    $localites = $form->get('localites')->getData();
                    if($localites instanceof ArrayCollection){
                        $localites = $localites->toArray();
                    }
                }
            }
            if(!is_array($localites)){
                $localites = array();
            }
        }

        if(!$isAll){
//           Comités
            if($request->request->has('check_all_comites')){
                $comites = $em->getRepository('SofieExpBundle:Comite')->getFullAll();
            }else{
                if($form->has('comites')){
                    $comites = $form->get('comites')->getData();
                    if($comites instanceof ArrayCollection){
                        $comites = $comites->toArray();
                    }
                }
            }
            if(!is_array($comites)){
                $comites = array();
            }

//           Réparateurs
           if($request->request->has('check_all_reparateurs')){
               $reparateurs = $em->getRepository('SofieExpBundle:Reparateur')->getTotalAll();
           }else{
               if($form->has('reparateurs')){
                   $reparateurs = $form->get('reparateurs')->getData();
                   if($reparateurs instanceof ArrayCollection){
                       $reparateurs = $reparateurs->toArray();
                   }
               }
           }
           if(!is_array($reparateurs)){
               $reparateurs = array();
           }

//           Agents Formen
            if($request->request->has('check_all_agents')){
               $agents = $em->getRepository('SofieExpBundle:Agent')->getAgents();
            }else{
               if($form->has('agents')){
                   $agents = $form->get('agents')->getData();
                   if($agents instanceof ArrayCollection){
                       $agents = $agents->toArray();
                   }
               }
            }
            if(!is_array($agents)){
               $agents = array();
            }

//           Sociologues
            if($request->request->has('check_all_sociologues')){
               $sociologues = $em->getRepository('SofieExpBundle:Agent')->getSociologues();
            }else{
                if($form->has('sociologues')){
                    $sociologues = $form->get('sociologues')->getData();
                    if($sociologues instanceof ArrayCollection){
                        $sociologues = $sociologues->toArray();
                    }
                }
            }
            if(!is_array($sociologues)){
               $sociologues = array();
            }

//           Directeurs
           if($request->request->has('check_all_directeurs')){
               $directeurs = $em->getRepository('SofieExpBundle:Agent')->getDirecteurs();
           }else{
               if($form->has('directeurs')){
                   $directeurs = $form->get('directeurs')->getData();
                   if($directeurs instanceof ArrayCollection){
                       $directeurs = $directeurs->toArray();
                   }
               }
           }
           if(!is_array($directeurs)){
               $directeurs = array();
           }

           $agents = array_merge($agents, $sociologues, $directeurs);
        }

//        Autres
        if($form->has('autres')){
            $autres = preg_split('/[\s,;|]+/', strval($form->get('autres')->getData()), -1, PREG_SPLIT_NO_EMPTY);
        }
        if(!is_array($autres)){
            $autres = array();
        }
        $autres = array_filter($autres, function($val){
            return (preg_match('/^(\d|\+)\d{7,14}[\s|,;-]*$/', strval($val))==1);
        });


//       Messages
        if($form->has('message')){
            $message = $form->get('message')->getData();
            if(!is_string($message)){
                $message = '';
            }
        }
        return array(
            'regions' => $regions,
            'localites' => $localites,
            'comites' => $comites,
            'reparateurs' => $reparateurs,
            'agents' => $agents,
            'autres' => $autres,
            'message' => $message
        );
    }

    protected function numbersArray(array $data)
    {
        $numbers = array();
        $regions = $data['regions'];
        $localites = $data['localites'];
        $comites = $data['comites'];
        $reparateurs = $data['reparateurs'];
        $agents = $data['agents'];

        if(!empty($comites) || !empty($reparateurs) || !empty($agents)){
            if(!empty($comites)){
                $numbers = array_unique(array_merge($numbers, $this->numbersByProfiles($comites)));
            }
            if(!empty($reparateurs)){
                $numbers = array_unique(array_merge($numbers, $this->numbersByProfiles($reparateurs)));
            }
            if(!empty($agents)){
                $numbers = array_unique(array_merge($numbers, $this->numbersByProfiles($agents)));
            }
        }elseif(!empty($localites)){
            $numbers = $this->numbersByLocalites($localites);
        }elseif(!empty($regions)){
            $numbers = $this->numbersByRegions($regions);
        }

        return array_unique($numbers);
    }

    protected function allNumbers()
    {
        $user = $this->getUser();
        if(!($user instanceof User)){
            $user = null;
        }
        $regions = $this->getDoctrine()->getManager()
            ->getRepository('SofieExpBundle:Region')->getByUserRegion($user);
        return $this->numbersByRegions($regions);
    }

    protected function numbersByRegions($regions)
    {
        $numbers = array();
        $em = $this->getDoctrine()->getManager();
        foreach($regions as $region){
            $numbers = array_unique(array_merge($numbers, $this->numbersByLocalites(
                $em->getRepository('SofieExpBundle:Localite')->getByRegion($region)
            )));
            $numbers = array_unique(array_merge($numbers, $this->numbersByProfiles(
                $em->getRepository('SofieExpBundle:Agent')->getByRegionAndMustInitialized($region)
            )));
        }
        return array_unique($numbers);
    }

    protected function numbersByLocalites($localites){
        $numbers = array();
        foreach($localites as $localite){
            $numbers = array_unique(array_merge($numbers, $this->numbersByProfiles($localite->getComites())));
            $reparateur = $localite->getReparateur();
            if(!is_null($reparateur) && !is_null($reparateur->getNumeroAppel()) && $reparateur->getNumeroAppel()->getNumero()!=''){
                $numbers[] = $reparateur->getNumeroAppel()->getNumero();
            }
            $agentFormen = $localite->getAgentForma();
            if(!is_null($agentFormen) && !is_null($agentFormen->getNumeroAppel()) && $agentFormen->getNumeroAppel()->getNumero()!=''){
                $numbers[] = $agentFormen->getNumeroAppel()->getNumero();
            }
        }
        return array_unique($numbers);
    }

    protected function numbersByProfiles($profiles)
    {
        $numbers = array();
        if(!empty($profiles)){
            foreach($profiles as $profile){
                if(method_exists($profile, 'getNumeroAppel')){
                    $numAppel = $profile->getNumeroAppel();
                    if(is_object($numAppel) && method_exists($numAppel, 'getNumero')){
                        if($numAppel && $numAppel->getNumero()!=''){
                            $numbers[] = $numAppel->getNumero();
                        }
                    }
                }
            }
        }
        return array_unique($numbers);
    }

    protected function critereForm(Region $region = null)
    {
        $builder = $this->createFormBuilder(); //array('csrf_protection' => false)
        if(is_null(self::$site)){
            $builder->add('regions', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Régions',
                'class'=>'SofieExpBundle:Region',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true
            ));
        }

        $builder
            ->add('localites', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Localités',
                'class'=>'SofieExpBundle:Localite',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true,
                'choices'=>(is_null($region)) ? array() : $region->getLocalites()
            ))
            ->add('reparateurs', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Réparateurs',
                'class'=>'SofieExpBundle:Reparateur',
                'empty_data'=>null,
                'multiple'=>true,
                'choices'=>array()
            ))
            ->add('comites', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Comités eaux',
                'class'=>'SofieExpBundle:Comite',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true,
                'choices'=>array()
            ))
            ->add('agents', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Agents Formen',
                'class'=>'SofieExpBundle:Agent',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true,
                'choices'=>array()
            ))
            ->add('sociologues', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Sociologues',
                'class'=>'SofieExpBundle:Agent',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true,
                'choices'=>array()
            ))
            ->add('directeurs', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Directeurs régionaux',
                'class'=>'SofieExpBundle:Agent',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true,
                'choices'=>array()
            ))
            ->add('autres', 'text', array(
                'required'=>false, 'trim'=>true, 'label'=>'Autres numéros',
                'attr'=>array('placeholder'=>'numéro1;numéro2;...;numéroN'),
                'constraints'=>array(
                    new Regex(array(
                        'pattern'=>'/^((\d|\+)\d{7,14}[\s|,;-]*)((\d|\+)\d{7,14}[\s|,;-]*)*((\d|\+)\d{7,14}[\s|,;-]*)?$/'
                    ))
                )
            ))
            ->add('message', 'textarea', array(
                'required'=>true, 'trim'=>true, 'label'=>'Message',
                'constraints'=>array(
                    new NotBlank(array('message'=>'Le message est obligatoire.')),
                    new Length(array('min'=>1))
                )
            ))
        ;

        $builder->get('localites')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
            $form = $event->getForm()->getParent();
            $this->normalizePostData($form);
        });

        return $builder->getForm();
    }

    protected function normalizePostData(FormInterface &$form){
        $form
            ->add('localites', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Localités',
                'class'=>'SofieExpBundle:Localite',
                'placeholder'=>'',
                'empty_data'=>null,
                'multiple'=>true
            ))
            ->add('comites', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Comités',
                'class'=>'SofieExpBundle:Comite',
                'empty_data'=>null,
                'multiple'=>true
            ))
            ->add('reparateurs', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Réparateurs',
                'class'=>'SofieExpBundle:Reparateur',
                'empty_data'=>null,
                'multiple'=>true
            ))
            ->add('agents', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Agents Formen',
                'class'=>'SofieExpBundle:Agent',
                'empty_data'=>null,
                'multiple'=>true,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getAgentsBuilder();
                }
            ))
            ->add('sociologues', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Sociologues',
                'class'=>'SofieExpBundle:Agent',
                'empty_data'=>null,
                'multiple'=>true,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getSociologuesBuilder();
                }
            ))
            ->add('directeurs', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Directeurs régionaux',
                'class'=>'SofieExpBundle:Agent',
                'empty_data'=>null,
                'multiple'=>true,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getDirecteursBuilder();
                }
            ))
        ;
    }
}
