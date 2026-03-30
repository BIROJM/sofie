<?php

namespace Sofie\AdminBundle\Controller;

use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class ProfilController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * @Security("has_role('ROLE_REPARATEUR')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if($page < 1){
            throw new \InvalidArgumentException("Page introuvable");
        }
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $form = $this->critereForm();
        $em = $this->getDoctrine()->getManager();
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array();
        $pdfCriteres='';
		
		//hniamkey
		
        if($form && $this->isCritere($request)){
                        
            $designation = $request->query->get('designation', null);
            if(!is_null($designation)){
                $designation = trim(urldecode($designation));
                $criteres['designation'] = $designation;
                if($form->has('designation')){
                    $form->get('designation')->setData($designation);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($designation != null) ? 'Designation="'.$designation.'", ' : '';
            }
        }
		
        if(!is_null($request->query->get('pdf', null))){
            return $this->pdfExport($criteres, $pdfCriteres);
        }
		
		$profils = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Profile')->findAll();
      //  $profils = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Profile')
        //    ->getByCriteres($criteres, $offset, $page);

        return $this->render('SofieAdminBundle:Profil:index.html.twig', array(
            'profils'=>$profils,
            "page"=>$page,
            'form' => ($form)?$form->createView():null
        ));
    }
	
	 protected function critereForm()
    {
        $form = $this->createFormBuilder() //array('csrf_protection' => false)
            ->add('designation', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Designation'))
            
            /*->add('acteur', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Acteur',
                'choices'=>array('1'=>'Agent', '2'=>'Comité', '3'=>'Réparateur'),
                'placeholder'=>'',
                'empty_data'=>null
            ))*/
            ->getForm();

        return $form;
    }
	
	 protected function isCritere(Request $request)
    {
      //  return boolval($request->query->get('desigantion', null));
    }
	
	/**
     * @Security("has_role('ROLE_ADD_GROUPE')")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $profile = new Profile();
        $form = $this->createForm('sofie_adminbundle_profil', $profile);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
//                $this->extractUserGroupes($form, $groupe);
                $apiAdd = $this->get('api_add');
				$profile->setSync('N');
                if($apiAdd->proccess($profile, 'Profile', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieadmin_profil_index'));
                }
            }
        }
        return $this->render('SofieAdminBundle:Profil:edit.html.twig', array(
            'form'=>$form->createView()            
        ));
    }

    /**
     * @Security("has_role('ROLE_EDIT_GROUPE')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $profil = $em->getRepository('SofieExpBundle:Profile')->get(intval($id));
        if(empty($profil)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_adminbundle_profil', $profil);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
//                $this->extractUserGroupes($form, $groupe);
				$profil->setSync('N');
				 $em->persist($profil);
                $em->flush();
                $this->logProfil($profil, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieadmin_profil_index'));
            }
        }
        return $this->render('SofieAdminBundle:Profil:edit.html.twig', array(
            'form'=>$form->createView()
            
        ));
    }

    /**
     * @Security("has_role('ROLE_GROUPE')")
     */
    public function viewAction($id)
    {
        $profil = $this->getDoctrine()->getRepository('SofieExpBundle:Profile')->get(intval($id));
        if(empty($profil)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logProfil($profil, 'consulté');

        return $this->render('SofieAdminBundle:Profil:view.html.twig', array('profil'=>$profil));
    }

    /**
     * @Security("has_role('ROLE_DELETE_GROUPE')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $profil = $em->getRepository('SofieExpBundle:Profile')->get(intval($id));
        if(empty($profil)){
            throw $this->createNotFoundException('Information introuvable');
        }
		if(count($profil->getAgents()) > 0)
		{
			$this->logProfil($profil, 'Impossible de supprimer');
			$this->get('ras_flash_alert.alert_reporter')->addSuccess("Impossible de supprimer, supprimez d'abord les utilisateurs !");			
		}
		else
		{
			$em->remove($profil);
			$em->flush();
			$this->logProfil($profil, 'supprimé');
			$this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");		
		}
       
        return $this->redirect($this->generateUrl('sofieadmin_profil_index'));
    }
	
	 protected function logProfil(Profile $profil, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Profil '.$action;
        if(method_exists($profil, 'logString')){
            $logMsg .= ' - infos ['.$profil->logString().']';
        }
        Logging::write($logMsg);
    }
	
	 public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $profils = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Profile')->findAll();
           // ->getFullByCriteres($criteres);
        $view = $this->renderView('SofieAdminBundle:Profil:export/list.pdf.twig', array(
            'profils'=>$profils, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

}
