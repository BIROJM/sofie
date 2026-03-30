<?php

namespace Sofie\ExpBundle\Controller;

use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\Agent;
use Sofie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\ExpBundle\Entity\Region;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class RegionController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * Security("has_role('ROLE_REGION')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        if(!is_null(self::$site)){
            $region = $em->getRepository('SofieExpBundle:Region')->find(intval(self::$site));
            return $this->forward('SofieExpBundle:Region:view', array('id'=>$region->getId()));
        }
        if(!is_null($request->query->get('pdf', null))){
            return $this->pdfExport();
        }
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $regions = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Region')->getAll($page, $offset);
        return $this->render('SofieExpBundle:Region:index.html.twig', array(
            'regions'=>$regions,
            "page"=>$page
        ));
    }

    public function pdfExport()
    {
        $regions = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Region')
            ->getFullAll();
        $view = $this->renderView('SofieExpBundle:Region:export/list.pdf.twig', array(
            'regions'=>$regions
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    /**
     * @Security("has_role('ROLE_EDIT_REGION')")
     */
    public function addAction(Request $request)
    {
        $this->accessChecker('ROLE_EDIT_REGION');
        $region = new Region();
        $form = $this->createForm('sofie_expbundle_region', $region);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($region, 'Region', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieexp_region_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:Region:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_REGION')")
     */
    public function editAction(Request $request, $id)
    {
        $this->accessChecker('ROLE_EDIT_REGION');
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('SofieExpBundle:Region')->get(intval($id));
        if(empty($region)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $form = $this->createForm('sofie_expbundle_region', $region);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->flush();
                $this->logRegion($region, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieexp_region_index'));
            }
        }
        return $this->render('SofieExpBundle:Region:edit.html.twig', array('form'=>$form->createView()));
    }

    protected function applyAgentsNumber(Region &$region, Form $form)
    {

    }

    /**
     * @Security("has_role('ROLE_REGION')")
     */
    public function viewAction($id)
    {
        $this->accessChecker('ROLE_REGION');
        $region = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Region')->get(intval($id));
        if(empty($region)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $this->logRegion($region, 'consulté');
        return $this->render('SofieExpBundle:Region:view.html.twig', array('region'=>$region));
    }

    /**
     * @Security("has_role('ROLE_DELETE_REGION')")
     */
    public function deleteAction($id)
    {
        $this->accessChecker('ROLE_DELETE_REGION');
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('SofieExpBundle:Region')->get(intval($id));
        if(empty($region)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $em->remove($region);
        $em->flush();
        $this->logRegion($region, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_region_index'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function loadCarteAction(Request $request, $id='')
    {
        if(intval($id) === 0){
            $url = trim(SessionInfo::getCarteCentralUrl($request->getSession()));
        }else{
            $url = rtrim(trim(SessionInfo::getCarteRegionUrl($request->getSession())), "/").'/'.$id;
        }
        $serverError = '<div class="alert alert-warning">'
            .'<i class="fa fa-warning"></i> '
            .'Impossible de contacter le serveur de la carte! Veillez contacter votre administrateur.'
            .'</div>';
        if(!$this->get('check_url')->check($url)){
            return new Response($serverError);
        }
        if(!$this->get('security.authorization_checker')->isGranted('ROLE_CARTE')){
            return new Response('');
        }
        try{
            if(($carte = file_get_contents($url)) !== false)
            {
                return new Response($carte);
            }else{
                return new Response('<i class="fa fa-warning"></i> Impossible de charger la carte !');
            }
        }catch (\Exception $e){
            return new Response($serverError);
        }

    }

    protected function logRegion(Region $region, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Region '.$action;
        if(method_exists($region, 'logString')){
            $logMsg .= ' - infos ['.$region->logString().']';
        }
        Logging::write($logMsg);
    }

    protected function accessChecker($role)
    {
        if(!$this->get('security.authorization_checker')->isGranted($role)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
    }


    /*
     * AJAX REQUEST
     * */

    protected function getAgentNumberAction($id)
    {
        $agent = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Agent')->get(intval($id));
        if(empty($agent)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $response = array('hasNumber'=>true);
        if(!is_null($agent->getNumeroAppel()) && trim($agent->getNumeroAppel()->getNumero()) != ''){
            $response['hasNumber'] = false;
        }

        return new Response(json_encode($response));
    }

    public function loadDependenciesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $list = $request->request->get('list', '');
        if(strlen($list) > 0){
            $list = explode(',', $list);
            $localites = $em->getRepository('SofieExpBundle:Localite')->getByRegionsIn($list);
            $reparateurs = $em->getRepository('SofieExpBundle:Reparateur')->getByRegionsIn($list);
            $comites = $em->getRepository('SofieExpBundle:Comite')->getByRegionsIn($list);
            $agents = $em->getRepository('SofieExpBundle:Agent')->getAgentsByRegionsIn($list);
            $sociologues = $em->getRepository('SofieExpBundle:Agent')->getSociologuesByRegionsIn($list);
            $directeurs = $em->getRepository('SofieExpBundle:Agent')->getDirecteursByRegionsIn($list);
        }else{
            $list = array();
            /*$localites = $em->getRepository('SofieExpBundle:Localite')->getByUserRegion();
            $reparateurs = $em->getRepository('SofieExpBundle:Reparateur')->getByUserRegion();
            $comites = $em->getRepository('SofieExpBundle:Comite')->getByUserRegion();
            $agents = $em->getRepository('SofieExpBundle:Agent')->getAgents();
            $sociologues = $em->getRepository('SofieExpBundle:Agent')->getSociologues();
            $directeurs = $em->getRepository('SofieExpBundle:Agent')->getDirecteurs();*/
            $localites = array();
            $reparateurs = array();
            $comites = array();
            $agents = array();
            $sociologues = array();
            $directeurs = array();
        }

        $localites = $this->getOptionsList($localites);
        $reparateurs = $this->getOptionsList($reparateurs);
        $comites = $this->getOptionsList($comites);
        $agents = $this->getOptionsList($agents);
        $sociologues = $this->getOptionsList($sociologues);
        $directeurs = $this->getOptionsList($directeurs);

        return new Response(json_encode(array(
            'localites'=>$localites,
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
