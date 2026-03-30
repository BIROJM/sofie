<?php

namespace Sofie\AdminBundle\Controller;

use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\Stati;
use Sofie\UserBundle\Entity\Groupe;
use Sofie\UserBundle\Entity\GroupeDroit;
use Sofie\UserBundle\Entity\UserGroupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class GroupeController extends Controller
{
    /**
     * @Security("has_role('ROLE_GROUPE')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $form = $this->critereForm();
        $criteres = array();
        $pdfCriteres = '';
        if($this->isCritere($request)){
            $nom = $request->query->get('nom', null);
            if(!is_null($nom)){
                $nom = trim(urldecode($nom));
                $criteres['nom'] = $nom;
                if($form->has('nom')){
                    $form->get('nom')->setData($nom);
                }
            }

            if(!is_null($request->query->get('pdf', null))){
                $pdfCriteres .= ($nom != null) ? 'Nom="'.$nom.'", ' : '';
            }
        }
        if(!is_null($request->query->get('pdf', null))){
            return $this->pdfExport($criteres, $pdfCriteres);
        }
        $groupes = $em->getRepository('SofieUserBundle:Groupe')->getByCriteres($criteres, $offset, $page);

        return $this->render('SofieAdminBundle:Groupe:index.html.twig', array(
            'groupes'=>$groupes,
            "page"=>$page,
            'form' => ($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $groupes = $this->getDoctrine()->getManager()->getRepository('SofieUserBundle:Groupe')
            ->getFullByCriteres($criteres);
        $view = $this->renderView('SofieAdminBundle:Groupe:export/list.pdf.twig', array(
            'groupes'=>$groupes, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (!is_null($request->query->get('nom', null)));
    }

    protected function critereForm()
    {
        $builder = $this->createFormBuilder()
            ->add('nom', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom'))
        ;
        return $builder->getForm();
    }

    /**
     * @Security("has_role('ROLE_ADD_GROUPE')")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = new Groupe();
        $droitCategories = $em->getRepository('SofieUserBundle:DroitCategory')->getAll();
        $form = $this->createForm('sofie_adminbundle_groupe', $groupe);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
//                $this->extractUserGroupes($form, $groupe);
                $this->extractGroupeDroits($request, $groupe);
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($groupe, 'Groupe', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieadmin_groupe_index'));
                }
            }
        }
        return $this->render('SofieAdminBundle:Groupe:edit.html.twig', array(
            'form'=>$form->createView(),
            'droitCategories'=>$droitCategories
        ));
    }

    /**
     * @Security("has_role('ROLE_EDIT_GROUPE')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('SofieUserBundle:Groupe')->get(intval($id));
        if(empty($groupe)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_adminbundle_groupe', $groupe);
        $droitCategories = $em->getRepository('SofieUserBundle:DroitCategory')->getAll();
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
//                $this->extractUserGroupes($form, $groupe);
                $this->extractGroupeDroits($request, $groupe);
                $em->flush();
                $this->logGroupe($groupe, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieadmin_groupe_index'));
            }
        }
        return $this->render('SofieAdminBundle:Groupe:edit.html.twig', array(
            'form'=>$form->createView(),
            'droitCategories'=>$droitCategories
        ));
    }

    /**
     * @Security("has_role('ROLE_GROUPE')")
     */
    public function viewAction($id)
    {
        $groupe = $this->getDoctrine()->getRepository('SofieUserBundle:Groupe')->get(intval($id));
        if(empty($groupe)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logGroupe($groupe, 'consulté');

        return $this->render('SofieAdminBundle:Groupe:view.html.twig', array('groupe'=>$groupe));
    }

    /**
     * @Security("has_role('ROLE_DELETE_GROUPE')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $groupe = $em->getRepository('SofieUserBundle:Groupe')->get(intval($id));
        if(empty($groupe)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em->remove($groupe);
        $em->flush();
        $this->logGroupe($groupe, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieadmin_groupe_index'));
    }

    protected function logGroupe(Groupe $groupe, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Groupe '.$action;
        if(method_exists($groupe, 'logString')){
            $logMsg .= ' - infos ['.$groupe->logString().']';
        }
        Logging::write($logMsg);
    }

    protected function extractUserGroupes(Form $form, Groupe &$groupe)
    {
        if($form->has('users')){
            $em = $this->getDoctrine()->getManager();
            $usersId = array();
            $em->getFilters()->disable('softdeleteable');
            foreach($form->get('users')->getData() as $user){
                $userGroupe = new UserGroupe();
                $userGroupe->setUser($user);
                $userGroupe->setGroupe($groupe);
                $userGroupeOld = $em->getRepository('SofieUserBundle:UserGroupe')
                    ->get($user, $userGroupe->getGroupe())
                ;
                if(!is_null($userGroupeOld)){
                    $userGroupeOld->setDeletedAt(null);
                    $groupe->addUserGroupe($userGroupeOld);
                }else{
                    $groupe->addUserGroupe($userGroupe);
                }
                $usersId[] = $user->getId();
            }
            $em->getFilters()->enable('softdeleteable');
            foreach($groupe->getUserGroupes() as $userGroupe){
                if(!in_array($userGroupe->getUser()->getId(), $usersId)){
                    $groupe->removeUserGroupe($userGroupe);
                    $em->remove($userGroupe);
                }
            }
        }

    }

    protected function extractGroupeDroits(Request $request, Groupe &$groupe)
    {
        /*if($form->has('droits')){
            $em = $this->getDoctrine()->getManager();
            $droitsId = array();
            $em->getFilters()->disable('softdeleteable');
            foreach($form->get('droits')->getData() as $droit){
                $groupeDroit = new GroupeDroit();
                $groupeDroit->setDroit($droit);
                $groupeDroit->setGroupe($groupe);
                $groupeDroitOld = $em->getRepository('SofieUserBundle:GroupeDroit')
                    ->findOneBy(array('groupe'=>$groupeDroit->getGroupe(), 'droit'=>$groupeDroit->getDroit()))
                ;
                if($groupeDroitOld){
                    $groupeDroitOld->setDeletedAt(null);
                    $groupe->addGroupeDroit($groupeDroitOld);

                }else{
                    $groupe->addGroupeDroit($groupeDroit);
                }
                $droitsId[] = $droit->getId();
            }
            $em->getFilters()->enable('softdeleteable');
            foreach($groupe->getGroupeDroits() as $groupeDroit){
                if(!in_array($groupeDroit->getDroit()->getId(), $droitsId)){
                    $groupe->removeGroupeDroit($groupeDroit);
                    $em->remove($groupeDroit);
                }
            }
        }*/
        $em = $this->getDoctrine()->getManager();
        $droitsId = $request->request->get('droits', array());;
        $em->getFilters()->disable('softdeleteable');
        foreach($droitsId as $id){
            $droit = $em->getRepository('SofieUserBundle:Droit')->get(intval($id));
            if(!is_null($droit)){
                $groupeDroit = new GroupeDroit();
                $groupeDroit->setGroupe($groupe);
                $groupeDroit->setDroit($droit);
                $groupeDroitOld = $em->getRepository('SofieUserBundle:GroupeDroit')
                    ->get($groupeDroit->getGroupe()->getId(), $droit->getId());
                ;
                if(!is_null($groupeDroitOld)){
                    $groupeDroitOld->setDeletedAt(null);
                    $groupe->addGroupeDroit($groupeDroitOld);

                }else{
                    $groupe->addGroupeDroit($groupeDroit);
                }
            }
        }
        $em->getFilters()->enable('softdeleteable');
        foreach($groupe->getGroupeDroits() as $groupeDroit){
            if(!in_array($groupeDroit->getDroit()->getId(), $droitsId)){
                $groupe->removeGroupeDroit($groupeDroit);
                $em->remove($groupeDroit);
            }
        }
    }
}
