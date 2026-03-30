<?php

namespace Sofie\ExpBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Entity\Config;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\Agent;
use Sofie\ExpBundle\Entity\Collecte;
use Sofie\ExpBundle\Entity\Comite;
use Sofie\ExpBundle\Entity\Panne;
use Sofie\ExpBundle\Entity\Region;
use Sofie\ExpBundle\Entity\TypePanne;
use Sofie\UserBundle\Entity\Droit;
use Sofie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\ExpBundle\Entity\Ouvrage;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Validator\Constraints\DateTime;


class OuvrageController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * @Security("has_role('ROLE_OUVRAGE')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        Ouvrage::initializeEDM($em);
        $region = $em->getRepository('SofieExpBundle:Region')->find(intval(urldecode($request->query->get('region', null))));
        $form = $this->critereForm($region);
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array();
        $pdfCriteres = '';

        if($this->isCritere($request)){
            if(!is_null($region)){
                $criteres['region'] = $region;
                if($form->has('region')){
                    $form->get('region')->setData($region);
                }
            }

            $localite = $em->getRepository('SofieExpBundle:Localite')->find(intval(urldecode($request->query->get('localite', null))));
            if(!is_null($localite)){
                $criteres['localite'] = $localite;
                if($form->has('localite')){
                    $form->get('localite')->setData($localite);
                }
            }

            $statut = $request->query->get('statut', null);
            if(!is_null($statut)){
                $statut = trim(urldecode($statut));
                $criteres['statut'] = $statut;
                if($form->has('statutOuvrage')){
                    $form->get('statutOuvrage')->setData($statut);
                }
            }

            $code = $request->query->get('code', null);
            if(!is_null($code)){
                $code = trim(urldecode($code));
                $criteres['code'] = $code;
                if($form->has('code')){
                    $form->get('code')->setData($code);
                }
            }

            $numIRH = $request->query->get('numIRH', null);
            if(!is_null($numIRH)){
                $numIRH = trim(urldecode($numIRH));
                $criteres['numIRH'] = $numIRH;
                if($form->has('numIRH')){
                    $form->get('numIRH')->setData($numIRH);
                }
            }

            $validated = $request->query->get('validated', null);
            if(!is_null($validated)){
                $validated = trim(urldecode($validated));
                $criteres['validated'] = $validated;
                if($form->has('validated')){
                    $form->get('validated')->setData($validated);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($region != null) ? 'Région="'.$region->getNom().'", ' : '';
                $pdfCriteres .= ($localite != null) ? 'Localité="'.$localite->getNom().'", ' : '';
                $pdfCriteres .= ($statut != null) ? 'Statut="'.$statut.'", ' : '';
                $pdfCriteres .= ($code != null) ? 'Code="'.$code.'", ' : '';
                $pdfCriteres .= ($numIRH != null) ? 'N° IRH="'.$numIRH.'", ' : '';
                $pdfCriteres .= ($validated!=null && array_key_exists($validated, Ouvrage::getValidatedWordArrayAssoc()))
                    ? 'Etat="'.Ouvrage::getValidatedWordArrayAssoc()[$validated].'", ' : '';
            }

        }

        if(!is_null($request->query->get('pdf', null))) {
            return $this->pdfExport($criteres, $pdfCriteres);
        }

        $ouvrages = $em->getRepository('SofieExpBundle:Ouvrage')->getByCriteres($criteres, $offset, $page);

        return $this->render('SofieExpBundle:Ouvrage:index.html.twig', array(
            'ouvrages'=>$ouvrages,
            "page"=>$page,
            'form'=>($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $ouvrages = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Ouvrage')
            ->getTotalByCriteres($criteres);
        $view = $this->renderView('SofieExpBundle:Ouvrage:export/list.pdf.twig', array(
            'ouvrages'=>$ouvrages, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (
            $request->query->get('region', null) || $request->query->get('localite', null)
            || !is_null($request->query->get('statut', null)) || !is_null($request->query->get('code', null))
            || !is_null($request->query->get('numIRH', null)) || !is_null($request->query->get('validated', null))
        );
    }

    protected function critereForm(Region $region = null)
    {
        $formBuilder = $this->createFormBuilder(); //array('csrf_protection' => false)
        if(is_null(self::$site)){
            $formBuilder->add('region', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label'=>'nom',
                'placeholder'=>'',
                'empty_data'=>null
            ));
        }else{
            if(is_null($region)){
                $region = $this->getDoctrine()->getManager()
                    ->getRepository('SofieExpBundle:Region')->find(intval(self::$site));
            }
        }

        $formBuilder->add('localite', 'entity', array(
            'required'=>false, 'trim'=>true, 'label'=>'Localité',
            'class'=>'SofieExpBundle:Localite',
            'choice_label'=>'nom',
            'placeholder'=>'',
            'empty_data'=>null,
            'query_builder'=>function(EntityRepository $er) use ($region){
                return $er->getByRegionBuilder($region);
            }
        ))
            ->add('statutOuvrage', 'choice', array(
                'required' => false, 'trim' => true, 'label'=>'Statut',
                'placeholder' => '',
                'choices' => Ouvrage::getStatutsOuvrage(),
                'empty_data'=>null
            ))
            ->add('validated', 'choice', array(
                'required' => false, 'trim' => true, 'label'=>'Etat',
                'placeholder' => '',
                'choices' => Ouvrage::getValidatedWordArrayAssoc(),
                'empty_data'=>null
            ))
            ->add('code', 'text', array('required' => false, 'trim' => true, 'label' => 'Code'))
            ->add('numIRH', 'text', array('required' => false, 'trim' => true, 'label' => 'N° IRH'))
        ;

        return $formBuilder->getForm();
    }

    /**
     * @Security("has_role('ROLE_ADD_OUVRAGE')")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $ouvrage = new Ouvrage;
        $ouvrage::initializeEDM($em);
        $currentId = intval($ouvrage->getComiteId());
        $form = $this->createForm('sofie_expbundle_ouvrage', $ouvrage);
        $comites = $em->getRepository('SofieExpBundle:Comite')
            ->getByLocaliteIdAndFree(intval($ouvrage->getLocaliteId()), $currentId);
        if($request->getMethod()==Request::METHOD_POST){
            $this->comiteHandler($request, $form, $currentId);
            $form->handleRequest($request);
            $comites = $em->getRepository('SofieExpBundle:Comite')
                ->getByLocaliteIdAndFree(intval($ouvrage->getLocaliteId()), $currentId);
            if($form->isValid()){
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccessOuvrage($ouvrage, $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieexp_ouvrage_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:Ouvrage:edit.html.twig', array(
            'form'=>$form->createView(),
            'comites'=>$comites,
            'currentId' => $currentId
        ));
    }

    /**
     * @Security("has_role('ROLE_EDIT_OUVRAGE')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ouvrage = $em->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $currentId = intval($ouvrage->getComiteId());
        $form = $this->createForm('sofie_expbundle_ouvrage', $ouvrage);
        $comites = $em->getRepository('SofieExpBundle:Comite')
            ->getByLocaliteIdAndFree(intval($ouvrage->getLocaliteId()), $currentId);
        if($request->getMethod()==Request::METHOD_POST){
            $this->comiteHandler($request, $form, $currentId);
            $form->handleRequest($request);
            $comites = $em->getRepository('SofieExpBundle:Comite')
                ->getByLocaliteIdAndFree(intval($ouvrage->getLocaliteId()), $currentId);
            if($form->isValid()){
				
			    $this->updateExtends($ouvrage, $em);
				
				if($ouvrage->getValidated())
				{
					if($numero = $form->get('statutOuvrage')->getData() == 'Panne')
					{
						$this->submitPanne($ouvrage, 2); 
					}
					elseif($form->get('statutOuvrage')->getData() == 'Marche')
					{
						$this->submitPanne($ouvrage, 1);
					}
				}				
                $em->persist($ouvrage);
                $em->flush();
                $this->logOuvrage($ouvrage, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieexp_ouvrage_index'));
            }
        }
        return $this->render('SofieExpBundle:Ouvrage:edit.html.twig', array(
            'form'=>$form->createView(),
            'comites'=>$comites,
            'currentId' => $currentId
        ));
    }

    protected function updateExtends(Ouvrage &$ouvrage, EntityManager $em)
    {
        $apiAdd = $this->get('api_add');

        // CoupeGeologique
        foreach($ouvrage->getCoupeGeologiques() as $coupe){
            if(is_null($em->getRepository('SofieExpBundle:CoupeGeologique')->find(intval($coupe->getId())))){
                $apiAdd->sendKey($coupe, 'CoupeGeologique', $this->getUser()->getUsername());
            }
        }

        // EquipementForage
        foreach($ouvrage->getEquipementForages() as $equip){
            if(is_null($em->getRepository('SofieExpBundle:EquipementForage')->find(intval($equip->getId())))){
                $apiAdd->sendKey($equip, 'EquipementForage', $this->getUser()->getUsername());
            }
        }

        // EquipementForage
        foreach($ouvrage->getEssaisPompages() as $essais){
            if(is_null($em->getRepository('SofieExpBundle:EssaisPompage')->find(intval($essais->getId())))){
                $apiAdd->sendKey($essais, 'EssaisPompage', $this->getUser()->getUsername());
            }
        }

        // SuiviPhysicoChimique
        foreach($ouvrage->getSuiviPhysicoChimiques() as $suivi){
            if(is_null($em->getRepository('SofieExpBundle:SuiviPhysicoChimique')->find(intval($suivi->getId())))){
                $apiAdd->sendKey($suivi, 'SuiviPhysicoChimique', $this->getUser()->getUsername());
            }
        }

        // VenuEauPrincipale
        foreach($ouvrage->getVenuEauPrincipales() as $venuEau){
            if(is_null($em->getRepository('SofieExpBundle:VenuEauPrincipale')->find(intval($venuEau->getId())))){
                $apiAdd->sendKey($venuEau, 'VenuEauPrincipale', $this->getUser()->getUsername());
            }
        }

    }

    /**
     * @Security("has_role('ROLE_OUVRAGE')")
     */
    public function viewAction($id)
    {
        $ouvrage = $this->getDoctrine()->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $this->logOuvrage($ouvrage, 'consulté');

        return $this->render('SofieExpBundle:Ouvrage:view.html.twig', array('ouvrage'=>$ouvrage));
    }

    /**
     * @Security("has_role('ROLE_DELETE_OUVRAGE')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $ouvrage = $em->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $em->remove($ouvrage);
        $em->flush();
        $this->logOuvrage($ouvrage, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_ouvrage_index'));
    }

    protected function comiteHandler(Request &$request, Form &$form, $currentId)
    {
        $form->add('comite', 'entity', array(
            'class'=>'SofieExpBundle:Comite', 'choice_label' => 'nom',
            'query_builder'=>function(EntityRepository $er) use ($request, $currentId){
                return $er->getByLocaliteIdAndFreeBuilder(
                    intval($request->request->get('sofie_expbundle_ouvrage', array('localite'=>'0'))['localite']),
                    $currentId
                );
            }
        ));
        $request->request->add(array('sofie_expbundle_ouvrage'=>array_merge(
            $request->request->get('sofie_expbundle_ouvrage'),
            array('comite'=>$request->request->get('sofie_expbundle_ouvrage_comite', null))
        )));
    }

    /**
     * @Security("has_role('ROLE_PANNE')")
     */
    public function pannesAction(Request $request, $id, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        $ouvrage = $em->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array('ouvrage'=>$ouvrage);
        $pdfCriteres = 'Code ouvrage="'.$ouvrage->getCode().'", ';
        $form = $this->criterePanneForm();

        if($this->isCriterePanne($request)){
            $dateApp = $request->query->get('dateApp', null);
            if(!is_null($dateApp)){
                if($form->has('dateApp')){
                    $form->get('dateApp')->setData($dateApp);
                }
                $dateApp = json_decode(trim(urldecode($dateApp)), true);
                $dateApp['start'] = (!empty($dateApp['start'])) ? new \DateTime($dateApp['start']) : new \DateTime('now');
                if(is_null($dateApp['start'])) $dateApp['start'] = new \DateTime('now');
                $dateApp['end'] = (!empty($dateApp['end'])) ? new \DateTime($dateApp['end']) : new \DateTime('now');
                if(is_null($dateApp['end'])) $dateApp['end'] = new \DateTime('now');
                $criteres['dateApp'] = $dateApp;
            }

            $datePriseCharge = $request->query->get('datePriseCharge', null);
            if(!is_null($datePriseCharge)){
                if($form->has('datePriseCharge')){
                    $form->get('datePriseCharge')->setData($datePriseCharge);
                }
                $datePriseCharge = json_decode(trim(urldecode($datePriseCharge)), true);
                $datePriseCharge['start'] = (!empty($datePriseCharge['start'])) ? new \DateTime($datePriseCharge['start']) : new \DateTime('now');
                if(is_null($datePriseCharge['start'])) $datePriseCharge['start'] = new \DateTime('now');
                $datePriseCharge['end'] = (!empty($datePriseCharge['end'])) ? new \DateTime($datePriseCharge['end']) : new \DateTime('now');
                if(is_null($datePriseCharge['end'])) $datePriseCharge['end'] = new \DateTime('now');
                $criteres['datePriseCharge'] = $datePriseCharge;
            }
            
            $dateDebutRep = $request->query->get('dateDebutRep', null);
            if(!is_null($dateDebutRep)){
                if($form->has('dateDebutRep')){
                    $form->get('dateDebutRep')->setData($dateDebutRep);
                }
                $dateDebutRep = json_decode(trim(urldecode($dateDebutRep)), true);
                $dateDebutRep['start'] = (!empty($dateDebutRep['start'])) ? new \DateTime($dateDebutRep['start']) : new \DateTime('now');
                if(is_null($dateDebutRep['start'])) $dateDebutRep['start'] = new \DateTime('now');
                $dateDebutRep['end'] = (!empty($dateDebutRep['end'])) ? new \DateTime($dateDebutRep['end']) : new \DateTime('now');
                if(is_null($dateDebutRep['end'])) $dateDebutRep['end'] = new \DateTime('now');
                $criteres['dateDebutRep'] = $dateDebutRep;
            }

            $dateReparation = $request->query->get('dateReparation', null);
            if(!is_null($dateReparation)){
                if($form->has('dateReparation')){
                    $form->get('dateReparation')->setData($dateReparation);
                }
                $dateReparation = json_decode(trim(urldecode($dateReparation)), true);
                $dateReparation['start'] = (!empty($dateReparation['start'])) ? new \DateTime($dateReparation['start']) : new \DateTime('now');
                if(is_null($dateReparation['start'])) $dateReparation['start'] = new \DateTime('now');
                $dateReparation['end'] = (!empty($dateReparation['end'])) ? new \DateTime($dateReparation['end']) : new \DateTime('now');
                if(is_null($dateReparation['end'])) $dateReparation['end'] = new \DateTime('now');
                $criteres['dateReparation'] = $dateReparation;
            }

            $type = $em->getRepository('SofieExpBundle:TypePanne')->find(intval(urldecode($request->query->get('type', null))));
            if(!is_null($type)){
                $criteres['typePanne'] = $type;
                if($form->has('type')){
                    $form->get('type')->setData($type);
                }
            }

            $numero = $request->query->get('numero', null);
            if(!is_null($numero)){
                $numero = trim(urldecode($numero));
                $criteres['numero'] = $numero;
                if($form->has('numero')){
                    $form->get('numero')->setData($numero);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($dateApp != null)
                    ? 'Date apparition="{début:'.$dateApp['start']->format('d/m/Y').', fin:'.$dateApp['end']->format('d/m/Y').'}", ' : '';
                $pdfCriteres .= ($datePriseCharge != null)
                    ? 'Date prise charge="{début:'.$datePriseCharge['start']->format('d/m/Y').', fin:'.$datePriseCharge['end']->format('d/m/Y').'}", ' : '';
                $pdfCriteres .= ($dateDebutRep != null)
                    ? 'Date début rep.="{début:'.$dateDebutRep['start']->format('d/m/Y').', fin:'.$dateDebutRep['end']->format('d/m/Y').'}", ' : '';
                $pdfCriteres .= ($dateReparation != null)
                    ? 'Date réparation="{début:'.$dateReparation['start']->format('d/m/Y').', fin:'.$dateReparation['end']->format('d/m/Y').'}", ' : '';
                $pdfCriteres .= ($type != null) ? 'Type="'.$type->getLibelle().'", ' : '';
                $pdfCriteres .= ($numero != null) ? 'Numéro="'.$numero.'", ' : '';
            }

        }
        if(!is_null($request->query->get('pdf', null))) {
            return PanneController::pdfExport($this, $criteres, $pdfCriteres);
        }
        $pannes = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Panne')
            ->getByCriteres($criteres, $offset, $page);
        return $this->render('SofieExpBundle:Ouvrage:pannes/index.html.twig', array(
            'ouvrage'=>$ouvrage,
            'pannes'=>$pannes,
            'page'=>$page,
            'form'=>($form)?$form->createView():null
        ));
    }

    protected function isCriterePanne(Request $request)
    {
        return (
            !is_null($request->query->get('dateApp', null)) || !is_null($request->query->get('datePriseCharge', null))
            || !is_null($request->query->get('dateDebutRep', null)) || !is_null($request->query->get('dateReparation', null))
            || $request->query->get('type', null) || !is_null($request->query->get('numero', null))
        );
    }

    protected function criterePanneForm()
    {
        $builder = $this->createFormBuilder(); //array('csrf_protection' => false)

        $builder
            ->add('dateApp', 'text', array('required' => false, 'trim' => true, 'label' => 'Date apparition'))
            ->add('datePriseCharge', 'text', array('required' => false, 'trim' => true, 'label' => 'Date prise charge'))
            ->add('dateDebutRep', 'text', array('required' => false, 'trim' => true, 'label' => 'Date début rep.'))
            ->add('dateReparation', 'text', array('required' => false, 'trim' => true, 'label' => 'Date réparation'))
            ->add('type', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Type panne',
                'class'=>'SofieExpBundle:TypePanne',
                'choice_label'=>'libelle',
                'placeholder'=>'',
                'empty_data'=>null
            ))
            ->add('numero', 'text', array('required' => false, 'trim' => true, 'label' => 'Numéro'))
        ;

        return $builder->getForm();
    }

    protected function logOuvrage(Ouvrage $ouvrage, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Ouvrage '.$action;
        if(method_exists($ouvrage, 'logString')){
            $logMsg .= ' - infos ['.$ouvrage->logString().']';
        }
        Logging::write($logMsg);
    }

    protected function logCollecte(Collecte $collecte, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Collecte '.$action;
        if(method_exists($collecte, 'logString')){
            $logMsg .= ' - infos ['.$collecte->logString().']';
        }
        Logging::write($logMsg);
    }


// AJAX REQUEST
    /**
     * @Security("has_role('ROLE_VALIDATE_OUVRAGE')")
     */
    public function ajaxValidateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ouvrage = $em->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $result['success'] = true;
        $result['message'] = 'Succcès !';
        if($ouvrage->isValidatable()){
            if($ouvrage->getValidated()){
                $ouvrage->setValidated(false);
                $this->logOuvrage($ouvrage, 'dévalidé');
            }else{
                $ouvrage->setValidated(true);
				if($ouvrage->getStatutPanne() == '2')
				{	
					$this->submitPanne($ouvrage, 2);
				}
                $this->logOuvrage($ouvrage, 'validé');				
            }
            if($result['success'] && $this->getUser() instanceof User){
                $ouvrage->setValidatedBy($this->getUser()->getAgent());
            }
            $result['validated'] = $ouvrage->getValidated();
            $em->flush();
        }else{
            $result['success'] = false;
            $result['message'] = $ouvrage->titleValidate();
            $this->logOuvrage($ouvrage, 'Validattion échouée, cause:{'.$result['message'].'}');
        }

        return new Response(json_encode($result));
    }

    /**
     * @Security("has_role('ROLE_APPEL')")
     */
    public function panneAppelsAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $panne = $em->getRepository('SofieExpBundle:Panne')->get(intval($id));
        if(empty($panne)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        if(!is_null($request->query->get('pdf', null))){
            $criteres = array('panne' => $panne->getNumero());
            $pdfCriteres = ($panne != null) ? 'Numéro de panne="'.$panne->getNumero().'", ' : '';
            return AppelController::pdfExport($this, $criteres, $pdfCriteres);
        }
        $appels = $em->getRepository('SofieExpBundle:AppelTelephonique')->getByPanne($panne);
        $json['content'] = $this->renderView('SofieExpBundle:Ouvrage:pannes/appels.html.twig', array(
            'panne'=>$panne,
            'appels'=>$appels
        ));
        $json['title'] = 'Appels téléphoniques concernants la panne n°'.$panne->getNumero();
        return new Response(json_encode($json, JSON_PRETTY_PRINT));
    }

    /**
     * @Security("has_role('ROLE_NOTIFICATION')")
     */
    public function panneNotificationsAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $panne = $em->getRepository('SofieExpBundle:Panne')->get(intval($id));
        if(empty($panne)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        if(!is_null($request->query->get('pdf', null))){
            $criteres = array('panne' => $panne);
            $pdfCriteres = ($panne != null) ? 'Numéro de panne="'.$panne->getNumero().'", ' : '';
            return NotificationController::pdfExport($this, $criteres, $pdfCriteres);
        }
        $notifications = $em->getRepository('SofieExpBundle:Notification')->getByPanne($panne);
        $json['content'] = $this->renderView('SofieExpBundle:Ouvrage:pannes/notifications.html.twig', array(
            'panne'=>$panne,
            'notifications'=>$notifications
        ));
        $json['title'] = 'Notifications concernants la panne n°'.$panne->getNumero();
        return new Response(json_encode($json, JSON_PRETTY_PRINT));
    }

    /**
     * @Security("has_role('ROLE_EDIT_TYPE_PANNE')")
     */
    public function editTypePanneAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $panne = $em->getRepository('SofieExpBundle:Panne')->get(intval($id));
        if(empty($panne)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        if($request->isXmlHttpRequest()){
            $form = $this->createFormBuilder($panne)
                ->add('typePanne', 'entity', array(
                    'required' => false, 'trim' => true, 'label' => 'Type de panne existant',
                    'class' => 'SofieExpBundle:TypePanne',
                    'choice_label' => 'libelle',
                    'empty_data' => null,
                    'placeholder' => ''
                ))
                ->add('newTypePanne', 'text', array(
                    'required' => false, 'trim' => true, 'label' => 'Nouveau type de panne', 'mapped'=>false
                ))
                ->getForm()
            ;
            $json['success'] = false;
            $json['title'] = 'Editer le type de la panne n°'.$panne->getNumero();
            if($request->getMethod() == Request::METHOD_POST){
                $form->handleRequest($request);
                if($form->isValid()){
                    if($form->has('newTypePanne') && $form->get('newTypePanne')->getData() != ''){
                        $newTypePanne = new TypePanne();
                        $newTypePanne->setLibelle($form->get('newTypePanne')->getData());
                        $panne->setTypePanne($newTypePanne);
                        $apiAdd = $this->get('api_add');
                        $result = $apiAdd->ajaxProccess($newTypePanne, 'TypePanne', $this->getUser()->getUsername());
                        $json['success'] = $result['success'];
                        $json['content'] = $panne->getTypePanne()->getLibelle();
                        $json['msg'] = $result['msg'];
                    }else{
                        $em->flush();
                        $json['success'] = true;
                        if($panne->getTypePanne() instanceof TypePanne){
                            $json['content'] = $panne->getTypePanne()->getLibelle();
                        }else{
                            $json['content'] = '';
                        }
                    }
                    $logMsg = '['.$this->getUser()->getUsername().'] Edition du type de la panne N°'.$panne->getNumero()
                        .' de l\ouvrage N°'.$panne->getOuvrage()->getCode();
                    if(method_exists($panne->getTypePanne(), 'logString')){
                        $logMsg .= ' - infos ['.$panne->getTypePanne()->logString().']';
                    }
                    Logging::write($logMsg);

                    return new Response(json_encode($json, JSON_PRETTY_PRINT));
                }
            }
            $json['content'] = $this->renderView('SofieExpBundle:Ouvrage:pannes/type_panne_form.html.twig', array(
                'form'=>$form->createView(),
                'panne'=>$panne
            ));

            return new Response(json_encode($json, JSON_PRETTY_PRINT));
        }
    }

	//hniamkey
	public function submitPanne($ouvrage, $statut, $origine = 2)
	{
		$em = $this->getDoctrine()->getManager();
		$conx = $em->getConnection();
		//recuperation de panne disponible
		$stmt0 = $conx->prepare("select f_getIDPanneByIDOuvrage(" . $ouvrage->getId() . ") as idPanne");
		$stmt0->execute();
		$result = $stmt0->fetchAll();
		$idPanne = $result[0]["idPanne"];
		
		$statutPanne = $em->getRepository('SofieExpBundle:StatutPanne')->find(intval($statut));
		
		if($idPanne == '0')
		{			
			if($statut == 2)
			{
				$panne = new Panne();
				//recuperation de ticket 
				$stmt = $conx->prepare('select f_generateTicket() as ticket');
				$stmt->execute();
				$result = $stmt->fetchAll();
				$ticket = $result[0]["ticket"];
				$panne->setNumero($ticket);
				$panne->setDateApparution('');
				$panne->setOrigine($origine);
				$panne->setOuvrage($ouvrage);
				$panne->setAlert(0);
				$panne->setSync('N');					
				$panne->setStatutPanne($statutPanne);
				$em->persist($panne);
				$em->flush();
				
				$this->getPanneSmsContent($statut, $ticket);
			}
		}
		else
		{
			if($statut == 1)
			{	
				
				$panne = $em->getRepository('SofieExpBundle:Panne')->find(intval($idPanne));
				
				$this->getPanneSmsContent($statut, $panne->getNumero());
				
				$panne->setDateReparation('');
				$panne->setOrigine($origine);
				$panne->setOuvrage($ouvrage);
				$panne->setSync('N');					
				$panne->setStatutPanne($statutPanne);
				$em->persist($panne);
				$em->flush();			
			}
		}
		
	}
	//hniamkey
	public function getPanneSmsContent($type, $numPanne = '')
	{
		$em = $this->getDoctrine()->getManager();
		$conx = $em->getConnection();
		
		$sql = "CALL P_SELECT_INFOS_OUVRAGE_BY_NUMPANNE(" . $numPanne . ", @_CODEOUVRAGE, @_IDREGION, @_NOMLOCALITE, @_NUMAPPEL_REP,
				@_NUMAGENT_FORMA, @_NUM_SOCIOLOGUE, @_NUM_COMITE, @_NOMREP, @_NOMAGENT_FORMA, @_NOM_SOCIOLOGUE, @_STATUTOUVRAGE)";
			
		$sql1 = "select  @_CODEOUVRAGE as CODEOUVRAGE, @_IDREGION as IDREGION, @_NOMLOCALITE as NOMLOCALITE, @_NUMAPPEL_REP as NUMAPPEL_REP,
				@_NUMAGENT_FORMA as NUMAGENT_FORMA, @_NUM_SOCIOLOGUE as NUM_SOCIOLOGUE, @_NUM_COMITE as NUM_COMITE, @_NOMREP as NOMREP, @_NOMAGENT_FORMA as NOMAGENT_FORMA, @_NOM_SOCIOLOGUE as NOM_SOCIOLOGUE, @_STATUTOUVRAGE as STATUTOUVRAGE";
		
		
		$p_call = $conx->prepare($sql);
		$p_call->execute();
		$query = $conx->prepare($sql1);
		$query->execute();				
			
		$tab = array();
		
		while($row = $query->fetch())
		{
			$tab[] = $row;
		}
					
		$numReparateur = $tab[0]['NUMAPPEL_REP'];//$p_result[0]['NUMAPPEL_REP'];			
		
		$numAgent = $tab[0]['NUMAGENT_FORMA'];//$p_result[0]['NUMAGENT_FORMA'];
		
		$codeOuvrage = $tab[0]['CODEOUVRAGE']; //$p_result[0]['CODEOUVRAGE'];
		
		$numAppel = '';
		
		$da = '';
		
		$msg = '';
		
		$ticket = $numPanne;
		
		if($type == 2)
		{
			$stmt0 = $conx->prepare("select * from t_sms_content where notification_sms = 'NOTIF_PANNE'");
			$stmt0->execute();
			$result = $stmt0->fetchAll();
			
			foreach($result as $sms)
			{
				if($sms['ID'] == '2' || $sms['ID'] == '3')
				{
					if($sms['ID'] == '2')
					{
						$da = str_replace('${sofie_NUMERO_REPARATEUR}', $numReparateur, $sms['DA']);
						$numAppel = $numReparateur;
						//$sql = "insert into t_notification (DateHeureNotif, IDNumAppel, IDPanne, MotifNotif, Receiver, Content) values('" . date("Y-m-d H:i:s") . "','" . $this->getIdNumAppel($numReparateur) . "','" . $this->getIdPanne($ticket) . "','2','" . $da ."' , '" . $msg . "')";
					}
					elseif($sms['ID'] == '3')
					{
						$da = str_replace('${sofie_NUMERO_AGENT}', $numAgent, $sms['DA']);	
						 $numAppel = $numAgent;
						//$sql = "insert into t_notification (DateHeureNotif, IDNumAppel, IDPanne, MotifNotif, Receiver, Content) values('" . date("Y-m-d H:i:s") . "','" . $this->getIdNumAppel($numAgent) . "','" . $this->getIdPanne($ticket) . "','2','" . $da ."' , '" . $msg . "')";
					}
					
					$msg = str_replace('${sofie_CODE_FORAGE}', $codeOuvrage, $sms['SMS_CONTENT']);
					$msg = str_replace('${sofie_TICKET_PANNE}', $ticket, $msg);
					
					$sql = "insert into t_notification (DateHeureNotif, IDNumAppel, IDPanne, MotifNotif, Receiver, Content, Sender, Origin) values('" . date("Y-m-d H:i:s") . "','" . $this->getIdNumAppel($numAppel) . "','" . $this->getIdPanne($ticket) . "','2','" . $da ."' , '" . $msg . "', '07268570', '2')";
					
					$stmt0 = $conx->prepare($sql);
					$stmt0->execute();	
				}				
			}
		}
		else
		{
			$stmt0 = $conx->prepare("select * from t_sms_content where notification_sms = 'NOTIF_REPARATION'");
			$stmt0->execute();
			$result = $stmt0->fetchAll();	
			
			foreach($result as $sms)
			{
				if($sms['ID'] == '5' || $sms['ID'] == '6')
				{
					if($sms['ID'] == '5')
					{
						$da = str_replace('${sofie_NUMERO_REPARATEUR}', $numReparateur, $sms['DA']);
						$numAppel = $numReparateur;
						//$sql = "insert into t_notification (DateHeureNotif, IDNumAppel, IDPanne, MotifNotif, Receiver, Content) values('" . date("Y-m-d H:i:s") . "','" . $this->getIdNumAppel($numReparateur) . "','" . $this->getIdPanne($ticket) . "','2','" . $da ."' , '" . $msg . "')";
					}
					elseif($sms['ID'] == '6')
					{
						$da = str_replace('${sofie_NUMERO_AGENT}', $numAgent, $sms['DA']);	
						$numAppel = $numAgent;
						//$sql = "insert into t_notification (DateHeureNotif, IDNumAppel, IDPanne, MotifNotif, Receiver, Content) values('" . date("Y-m-d H:i:s") . "','" . $this->getIdNumAppel($numAgent) . "','" . $this->getIdPanne($ticket) . "','2','" . $da ."' , '" . $msg . "')";
					}
					
					$msg = str_replace('${sofie_CODE_FORAGE}', $codeOuvrage, $sms['SMS_CONTENT']);
					$msg = str_replace('${sofie_TICKET_PANNE}', $ticket, $msg);
					
					$sql = "insert into t_notification (DateHeureNotif, IDNumAppel, IDPanne, MotifNotif, Receiver, Content, Sender, Origin) values('" . date("Y-m-d H:i:s") . "','" . $this->getIdNumAppel($numAppel) . "','" . $this->getIdPanne($ticket) . "','5','" . $da ."' , '" . $msg . "', '07268570', '2')";
					
					$stmt0 = $conx->prepare($sql);
					$stmt0->execute();	
				}				
			}
		}			
		 
	}
	
	public function getIdNumAppel($numAppel)
	{
		$em = $this->getDoctrine()->getManager();
		$conx = $em->getConnection();
		
		$stmt0 = $conx->prepare("select f_getIDNumappelByNumAppel('" . $numAppel . "') as numAppel");
		$stmt0->execute();
		$result = $stmt0->fetchAll();
		return $result[0]["numAppel"];
	}
	
	public function getIdPanne($idPanne)
	{
		$em = $this->getDoctrine()->getManager();
		$conx = $em->getConnection();
		
		$stmt0 = $conx->prepare("select f_getPanneIdByPanneTicket('" . $idPanne . "') as idPanne");
		$stmt0->execute();
		$result = $stmt0->fetchAll();
		return $result[0]["idPanne"];
	}

}
