<?php

namespace Sofie\ExpBundle\Controller;

use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\Collecte;
use Sofie\ExpBundle\Entity\Ouvrage;
use Sofie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class CollecteController extends Controller
{
    /**
     * @Security("has_role('ROLE_COLLECTE')")
     */
    public function indexAction(Request $request, $id, $page=1)
    {
        $ouvrage = $this->getDoctrine()->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array("ouvrage"=>$ouvrage);
        $pdfCriteres = 'Code ouvrage="'.$ouvrage->getCode().'", ';
        $form = $this->critereForm();
        if($this->isCritere($request)){
            $validated = $request->query->get('validated', null);
            if(!is_null($validated)){
                $validated = trim(urldecode($validated));
                $criteres['validated'] = $validated;
                if($form->has('validated')){
                    $form->get('validated')->setData($validated);
                }
            }

            $operateur = $request->query->get('operateur', null);
            if(!is_null($operateur)){
                $operateur = trim(urldecode($operateur));
                $criteres['operateur'] = $operateur;
                if($form->has('operateur')){
                    $form->get('operateur')->setData($operateur);
                }
            }

            $agentSaisie = $request->query->get('agentSaisie', null);
            if(!is_null($agentSaisie)){
                $agentSaisie = trim(urldecode($agentSaisie));
                $criteres['agentSaisie'] = $agentSaisie;
                if($form->has('agentSaisie')){
                    $form->get('agentSaisie')->setData($agentSaisie);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($validated!=null && array_key_exists($validated, Ouvrage::getValidatedWordArrayAssoc()))
                    ? 'Statut="'.Ouvrage::getValidatedWordArrayAssoc()[$validated].'", ' : '';
                $pdfCriteres .= ($operateur != null) ? 'Opérateur="'.$operateur.'", ' : '';
                $pdfCriteres .= ($agentSaisie != null) ? 'Agent de saisie="'.$agentSaisie.'", ' : '';
            }
        }
        if(!is_null($request->query->get('pdf', null))) {
            return $this->pdfExport($this, $criteres, $pdfCriteres);
        }
        $collectes = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Collecte')
            ->getByCriteres($criteres, $offset, $page);
        return $this->render('SofieExpBundle:Ouvrage:collectes/index.html.twig', array(
            'ouvrage'=>$ouvrage,
            'collectes'=>$collectes,
            'page'=>$page,
            'form'=>($form)?$form->createView():null
        ));
    }

    static public function pdfExport(Controller $controller, $criteres = array(), $pdfCriteres='')
    {
        $collectes = $controller->getDoctrine()->getManager()->getRepository('SofieExpBundle:Collecte')
            ->getTotalByCriteres($criteres);
        $view = $controller->renderView('SofieExpBundle:Ouvrage:collectes/export/list.pdf.twig', array(
            'collectes'=>$collectes, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($controller->get('tfox.mpdfport'), $view);
    }


    protected function isCritere(Request $request)
    {
        return (
            !is_null($request->query->get('validated', null)) || !is_null($request->query->get('operateur', null))
            || !is_null($request->query->get('agentSaisie', null))
        );
    }

    protected function critereForm()
    {
        $builder = $this->createFormBuilder() //array('csrf_protection' => false)
            ->add('validated', 'choice', array(
                'required' => false, 'trim' => true, 'label'=>'Etat',
                'placeholder' => '',
                'choices' => Collecte::getValidatedWordArrayAssoc(),
                'empty_data'=>null
            ))
            ->add('operateur', 'text', array('required' => false, 'trim' => true, 'label' => 'Opérateur'))
            ->add('agentSaisie', 'text', array('required' => false, 'trim' => true, 'label' => 'Agent de saisie'))
        ;

        return $builder->getForm();
    }


    /**
     * @Security("has_role('ROLE_ADD_COLLECTE')")
     */
    public function addAction(Request $request, $id)
    {
        $ouvrage = $this->getDoctrine()->getRepository('SofieExpBundle:Ouvrage')->get(intval($id));
        if(empty($ouvrage)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $collecte = new Collecte();
        $form = $this->createForm('sofie_expbundle_collecte', $collecte);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $collecte->setOuvrage($ouvrage);
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($collecte, 'Collecte', $this->getUser()->getUsername())){
                    //return $this->redirect($this->generateUrl('sofieexp_ouvrage_collectes', array('id'=>$ouvrage->getId())));
					return $this->redirect($this->generateUrl('sofieexp_collecte_index', array('id'=>$ouvrage->getId())));
					// return $this->render('SofieExpBundle:Ouvrage:collectes/edit.html.twig',  sofieexp_collecte_index
                }
            }
        }
        return $this->render('SofieExpBundle:Ouvrage:collectes/edit.html.twig', array(
            'form'=>$form->createView(),
            'ouvrage' => $ouvrage
        ));
    }

    /**
     * @Security("has_role('ROLE_EDIT_COLLECTE')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $collecte = $em->getRepository('SofieExpBundle:Collecte')->get(intval($id));
        if(empty($collecte)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $ouvrage = $collecte->getOuvrage();

        $form = $this->createForm('sofie_expbundle_collecte', $collecte);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){

                $collecte->setOuvrage($ouvrage);
                $em->persist($collecte);
                $em->flush();
                $this->logCollecte($collecte, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
               // return $this->redirect($this->generateUrl('sofieexp_ouvrage_collectes', array('id'=>$ouvrage->getId())));
			   return $this->redirect($this->generateUrl('sofieexp_collecte_index', array('id'=>$ouvrage->getId())));
            }
        }
        return $this->render('SofieExpBundle:Ouvrage:collectes/edit.html.twig', array(
            'form'=>$form->createView(),
            'ouvrage' => $ouvrage
        ));
    }

    /**
     * @Security("has_role('ROLE_COLLECTE')")
     */
    public function viewAction($id)
    {
        $collecte = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Collecte')->get(intval($id));
        if(empty($collecte)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $this->logCollecte($collecte, 'consulté');

        return $this->render('SofieExpBundle:Ouvrage:collectes/view.html.twig', array('collecte'=>$collecte));
    }

    /**
     * @Security("has_role('ROLE_DELETE_COLLECTE')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $collecte = $em->getRepository('SofieExpBundle:Collecte')->get(intval($id));
        if(empty($collecte)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $ouvrage = $collecte->getOuvrage();
        $em->remove($collecte);
        $em->flush();
        $this->logCollecte($collecte, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        //return $this->redirectToRoute('sofieexp_ouvrage_collectes', array('id'=>$ouvrage->getId()));
		return $this->redirect($this->generateUrl('sofieexp_collecte_index', array('id'=>$ouvrage->getId())));
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
     * @Security("has_role('ROLE_VALIDATE_COLLECTE')")
     */
    public function ajaxValidateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $collecte = $em->getRepository('SofieExpBundle:Collecte')->get(intval($id));
        if(empty($collecte)){
            throw $this->createNotFoundException('Information introuvable !');
        }
        $result['success'] = true;
        $result['message'] = 'Succcès !';
        if($collecte->isValidatable()){
            if($collecte->getValidated()){
                $collecte->setValidated(false);
                $this->logCollecte($collecte, 'dévalidée');
            }else{
                $collecte->setValidated(true);
                $this->logCollecte($collecte, 'validée');
            }
            $result['validated'] = $collecte->getValidated();
            if($this->getUser() instanceof User){
                $collecte->setValidatedBy($this->getUser()->getAgent());
            }
            $em->flush();
        }else{
            $result['success'] = false;
            $result['message'] = $collecte->titleValidate();
            $this->logCollecte($collecte, 'Validattion échouée, cause:{'.$result['message'].'}');
        }

        return new Response(json_encode($result));
    }
}
