<?php

namespace Sofie\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Controller\AppelController;
use Sofie\ExpBundle\Controller\NotificationController;
use Sofie\ExpBundle\Entity\NumeroAppel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class NumeroAppelController extends Controller
{
    /**
     * @Security("has_role('ROLE_NUM_APPEL')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $form = $this->critereForm();
        $em = $this->getDoctrine()->getManager();
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        $criteres = array();
        $pdfCriteres = '';

        if($this->isCritere($request)){
            $numero = $request->query->get('numero', null);
            $profile = $em->getRepository('SofieExpBundle:Profile')->find(intval($request->query->get('profile', null)));
            $acteur = $request->query->get('acteur', null);
            if($form->has('numero')){
                $form->get('numero')->setData($numero);
            }
            if($form->has('profile')){
                $form->get('profile')->setData($profile);
            }
            /*if($form->has('acteur')){
                $form->get('acteur')->setData($acteur);
            }*/
            $criteres = array('numero'=>$numero, 'profile'=>$profile, 'acteur'=>$acteur);
            $pdfCriteres .= ($numero != null) ? 'Numéro="'.$numero.'", ' : '';
            $pdfCriteres .= ($profile != null) ? 'Profil="'.$profile->getDesignation().'", ' : '';
        }
        if(!is_null($request->query->get('pdf', null))) {
            return $this->pdfExport($criteres, $pdfCriteres);
        }
        $numeroAppels = $em->getRepository('SofieExpBundle:NumeroAppel')->getByCriteres($criteres, $offset, $page);
        return $this->render('SofieAdminBundle:NumeroAppel:index.html.twig', array(
            'numeroAppels'=>$numeroAppels,
            "page"=>$page,
            'form' => ($form) ? $form->createView() : 0
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $numeroAppels = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:NumeroAppel')
            ->getFullByCriteres($criteres);
        $view = $this->renderView('SofieAdminBundle:NumeroAppel:export/list.pdf.twig', array(
            'numeroAppels'=>$numeroAppels, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return boolval($request->query->get('numero', null) || $request->query->get('profile', null) || $request->query->get('acteur', null));
    }

    protected function critereForm()
    {
        $form = $this->createFormBuilder() //array('csrf_protection' => false)
            ->add('numero', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Numéro'))
            ->add('profile', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Profil',
                'class'=>'SofieExpBundle:Profile',
                'property'=>'designation',
                'placeholder'=>'',
                'empty_data'=>null,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getByMustInitializedBuilder();
                }
            ))
            /*->add('acteur', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Acteur',
                'choices'=>array('1'=>'Agent', '2'=>'Comité', '3'=>'Réparateur'),
                'placeholder'=>'',
                'empty_data'=>null
            ))*/
            ->getForm();

        return $form;
    }

    /**
     * @Security("has_role('ROLE_APPEL')")
     */
    public function appelsAction(Request $request, $id, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        $numeroAppel = $em->getRepository('SofieExpBundle:NumeroAppel')->get(intval($id));
        if(empty($numeroAppel)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        if(!is_null($request->query->get('pdf', null))) {
            $criteres = array('numeroAppel'=>$numeroAppel->getNumero());
            $pdfCriteres = 'Numéro d\'appel="'.$numeroAppel->getNumero().'"';
            return AppelController::pdfExport($this, $criteres, $pdfCriteres);
        }
        $appels = $em->getRepository('SofieExpBundle:AppelTelephonique')->getByNumeroAppel($numeroAppel, $offset, $page);

        return $this->render('SofieAdminBundle:NumeroAppel:appels.html.twig', array(
            'numeroAppel'=>$numeroAppel,
            "page"=>$page,
            'appels' => $appels
        ));
    }

    /**
     * @Security("has_role('ROLE_NOTIFICATION')")
     */
    public function notificationsAction(Request $request, $id, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
        $numeroAppel = $em->getRepository('SofieExpBundle:NumeroAppel')->get(intval($id));
        if(empty($numeroAppel)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $offset = SessionInfo::getPaginationOffset($request->getSession());
        if(!is_null($request->query->get('pdf', null))){
            $criteres = array('numeroAppel' => $numeroAppel);
            $pdfCriteres = ($numeroAppel != null) ? 'Numéro de panne="'.$numeroAppel->getNumero().'", ' : '';
            return NotificationController::pdfExport($this, $criteres, $pdfCriteres);
        }
        $notifications = $em->getRepository('SofieExpBundle:Notification')->getByNumeroAppel($numeroAppel, $offset, $page);

        return $this->render('SofieAdminBundle:NumeroAppel:notifications.html.twig', array(
            'numeroAppel'=>$numeroAppel,
            "page"=>$page,
            'notifications' => $notifications
        ));
    }
}
