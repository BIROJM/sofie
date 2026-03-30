<?php

namespace Sofie\ExpBundle\Controller;

use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\TypePanne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class TypePanneController extends Controller
{
    /**
     * @Security("has_role('ROLE_TYPE_PANNE')")
     */
    public function indexAction(Request $request, $page=1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $form = $this->critereForm();
        $criteres = array();
        $pdfCriteres = '';
        if($this->isCritere($request)){
            $libelle = $request->query->get('libelle', null);
            if(!is_null($libelle)){
                $libelle = trim(urldecode($libelle));
                $criteres['libelle'] = $libelle;
                if($form->has('libelle')){
                    $form->get('libelle')->setData($libelle);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($libelle != null) ? 'Libellé="'.$libelle.'", ' : '';
            }
        }
        if(!is_null($request->query->get('pdf', null))) {
            return $this->pdfExport($criteres, $pdfCriteres);
        }
        $typePannes = $em->getRepository('SofieExpBundle:TypePanne')->getByCriteres($criteres, $offset, $page);
        return $this->render('SofieExpBundle:TypePanne:index.html.twig', array(
            'typePannes'=>$typePannes,
            "page"=>$page,
            'form'=>($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $typePannes = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:TypePanne')
            ->getTotalByCriteres($criteres);
        $view = $this->renderView('SofieExpBundle:TypePanne:export/list.pdf.twig', array(
            'typePannes'=>$typePannes, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (!is_null($request->query->get('libelle', null)));
    }

    protected function critereForm()
    {
        $builder = $this->createFormBuilder()
            ->add('libelle', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Libellé'))
        ;
        return $builder->getForm();
    }

    /**
     * @Security("has_role('ROLE_EDIT_TYPE_PANNE')")
     */
    public function addAction(Request $request)
    {
        $typePanne = new TypePanne();
        $form = $this->createFormBuilder($typePanne)
            ->add('libelle', 'text', array(
                'required' => true, 'trim' => true, 'label' => 'Libellé',
            ))
            ->getForm()
        ;
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($typePanne, 'TypePanne', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieexp_typepanne_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:TypePanne:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_TYPE_PANNE')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $typePanne = $em->getRepository('SofieExpBundle:TypePanne')->get(intval($id));
        if(empty($typePanne)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createFormBuilder($typePanne)
            ->add('libelle', 'text', array(
                'required' => true, 'trim' => true, 'label' => 'Libellé',
            ))
            ->getForm()
        ;
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->persist($typePanne);
                $em->flush();
                $this->logTypePanne($typePanne, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($request->getSession()->get($this->getParameter('referer_sessname'))[$request->getRequestUri()]);
//                return $this->redirect($this->generateUrl('sofieexp_typepanne_index'));
            }
        }
        return $this->render('SofieExpBundle:TypePanne:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_DELETE_TYPE_PANNE')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $typePanne = $em->getRepository('SofieExpBundle:TypePanne')->get(intval($id));
        if(empty($typePanne)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em->remove($typePanne);
        $em->flush();
        $this->logTypePanne($typePanne, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_typepanne_index'));
    }

    protected function logTypePanne(TypePanne $typePanne, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] TypePanne '.$action;
        if(method_exists($typePanne, 'logString')){
            $logMsg .= ' - infos ['.$typePanne->logString().']';
        }
        Logging::write($logMsg);
    }
    
    
    /*
     * AJAX ACTION
     * */
    public function ajaxSelectAddAction(Request $request)
    {
        if($request->getMethod()==Request::METHOD_POST){
            if($request->isXmlHttpRequest()){
                return new Response(array('success'=>true), JSON_PRETTY_PRINT);
            }
        }
        return new Response(json_encode(array('nobody'=>''), JSON_PRETTY_PRINT));
    }
}
