<?php

namespace Sofie\ExpBundle\Controller;

use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\ExpBundle\Entity\Localite;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class LocaliteController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();

    }

    /**
     * @Security("has_role('ROLE_LOCALITE')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $form = $this->critereForm();
        $em = $this->getDoctrine()->getManager();
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array();
        $pdfCriteres = '';
        if($form && $this->isCritere($request)){
            $region = $em->getRepository('SofieExpBundle:Region')->find(intval(urldecode($request->query->get('region', null))));
            if(!is_null($region)){
                $criteres['region'] = $region;
                if($form->has('region')){
                    $form->get('region')->setData($region);
                }
            }

            $nom = $request->query->get('nom', null);
            if(!is_null($nom)){
                $nom = trim(urldecode($request->query->get('nom', null)));
                $criteres['nom'] = $nom;
                if($form->has('nom')){
                    $form->get('nom')->setData($nom);
                }
            }

            if(!is_null($request->query->get('pdf', null))){
                $pdfCriteres .= ($region != null) ? 'Région="'.$region->getNom().'", ' : '';
                $pdfCriteres .= ($nom != null) ? 'Nom="'.$nom.'", ' : '';
            }
        }
        if(!is_null($request->query->get('pdf', null))){
            $localites = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Localite')
                ->getTotalByCriteres($criteres);
            $view = $this->renderView('SofieExpBundle:Localite:export/list.pdf.twig', array(
                'localites'=>$localites, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
            ));
            return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
        }
        $localites = $em->getRepository('SofieExpBundle:Localite')->getByCriteres($criteres, $offset, $page);

        return $this->render('SofieExpBundle:Localite:index.html.twig', array(
            'localites'=>$localites,
            "page"=>$page,
            'form' => ($form)?$form->createView():null
        ));
    }

    protected function isCritere(Request $request)
    {
        return ($request->query->get('region', null) || !is_null($request->query->get('nom', null)));
    }

    protected function critereForm()
    {
        $form = $this->createFormBuilder()
            ->add('nom', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom'));
        if(is_null(self::$site)){
            $form->add('region', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label'=>'nom',
                'placeholder'=>'',
                'empty_data'=>null
            ));
        }
        return $form->getForm();
    }

    /**
     * @Security("has_role('ROLE_ADD_LOCALITE')")
     */
    public function addAction(Request $request)
    {
        $localite = new Localite();
        $form = $this->createForm('sofie_expbundle_localite', $localite);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($localite, 'Localite', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieexp_localite_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:Localite:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_LOCALITE')")
     */
    public function editAction(Request $request, $id)
    {
        $localite = $this->getDoctrine()->getRepository('SofieExpBundle:Localite')->get(intval($id));
        if(empty($localite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_expbundle_localite', $localite);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $em->persist($localite);
                $em->flush();
                $this->logLocalite($localite, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieexp_localite_index'));
            }
        }
        return $this->render('SofieExpBundle:Localite:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_LOCALITE')")
     */
    public function viewAction($id)
    {
        $localite = $this->getDoctrine()->getRepository('SofieExpBundle:Localite')->get(intval($id));
        if(empty($localite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logLocalite($localite, 'consulté');

        return $this->render('SofieExpBundle:Localite:view.html.twig', array('localite'=>$localite));
    }

    /**
     * @Security("has_role('ROLE_DELETE_LOCALITE')")
     */
    public function deleteAction($id)
    {
        $localite = $this->getDoctrine()->getRepository('SofieExpBundle:Localite')->get(intval($id));
        if(empty($localite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($localite);
        $em->flush();
        $this->logLocalite($localite, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_localite_index'));
    }

    protected function logLocalite(Localite $localite, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Localite '.$action;
        if(method_exists($localite, 'logString')){
            $logMsg .= ' - infos ['.$localite->logString().']';
        }
        Logging::write($logMsg);
    }

    protected function accessChecker($role)
    {
        if(!$this->get('security.authorization_checker')->isGranted($role)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
    }


   /* AJAX REQUEST */
    public function ajaxByRegionAction($id)
    {
        $region = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Region')->find($id);
        $text = '<option value=""></option>';
        if(!($region instanceof Region)){
            return new Response($text);
        }

        $localites = $region->getLocalites();
        foreach($localites as $localite){
            $text .= '<option value="'.$localite->getId().'">'.$localite->getNom().'</option>';
        }
        return new Response($text);
    }

    public function loadDependenciesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $request->request->get('list', '');
        if(strlen($list) > 0){
            $list = explode(',', $list);
            $reparateurs = $em->getRepository('SofieExpBundle:Reparateur')->getByLocalitesIn($list);
            $comites = $em->getRepository('SofieExpBundle:Comite')->getByLocalitesIn($list);
            $agents = $em->getRepository('SofieExpBundle:Agent')->getAgentsByLocalitesIn($list);
            $sociologues = $em->getRepository('SofieExpBundle:Agent')->getSociologuesByLocalitesIn($list);
            $directeurs = $em->getRepository('SofieExpBundle:Agent')->getDirecteursByLocalitesIn($list);
        }else{
            $list = array();
            /*$reparateurs = $em->getRepository('SofieExpBundle:Reparateur')->getByUserRegion();
            $comites = $em->getRepository('SofieExpBundle:Comite')->getByUserRegion();
            $agents = $em->getRepository('SofieExpBundle:Agent')->getAgents();
            $sociologues = $em->getRepository('SofieExpBundle:Agent')->getSociologues();
            $directeurs = $em->getRepository('SofieExpBundle:Agent')->getDirecteurs();*/
            $reparateurs = array();
            $comites = array();
            $agents = array();
            $sociologues = array();
            $directeurs = array();
        }

        $reparateurs = $this->getOptionsList($reparateurs);
        $comites = $this->getOptionsList($comites);
        $agents = $this->getOptionsList($agents);
        $sociologues = $this->getOptionsList($sociologues);
        $directeurs = $this->getOptionsList($directeurs);

        return new Response(json_encode(array(
            'reparateurs'=>$reparateurs,
            'comites'=>$comites,
            'agents'=>$agents,
            'sociologues'=>$sociologues,
            'directeurs'=>$directeurs
        )));
    }

    protected function getOptionsList($collections = null)
    {
        $result = '';
        if(boolval($collections) && $collections != null){
            foreach($collections as $col){
                $result .= '<option value="'.$col->getId().'">'.$col.'</option>';
            }
        }
        return $result;
    }

}
