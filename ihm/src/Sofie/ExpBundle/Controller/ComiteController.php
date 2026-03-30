<?php

namespace Sofie\ExpBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\Localite;
use Sofie\ExpBundle\Entity\NumeroAppel;
use Sofie\ExpBundle\Entity\Profile;
use Sofie\ExpBundle\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\ExpBundle\Entity\Comite;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ComiteController extends Controller
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * @Security("has_role('ROLE_COMITE')")
     */
    public function indexAction(Request $request, $page = 1)
    {
        if(!$request->query->get(Stati::pagerMark(), null)) $page=1;
        $em = $this->getDoctrine()->getManager();
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

            $initStatus = $request->query->get('initStatus', null);
            if(!is_null($initStatus)){
                $initStatus = trim(urldecode($initStatus));
                $criteres['initStatus'] = $initStatus;
                if($form->has('initStatus')){
                    $form->get('initStatus')->setData($initStatus);
                }
            }

            $codeInit = $request->query->get('codeInit', null);
            if(!is_null($codeInit)){
                $codeInit = trim(urldecode($codeInit));
                $criteres['codeInit'] = $codeInit;
                if($form->has('codeInit')){
                    $form->get('codeInit')->setData($codeInit);
                }
            }

            $numeroAppel = $request->query->get('numeroAppel', null);
            if(!is_null($numeroAppel)){
                $numeroAppel = trim(urldecode($numeroAppel));
                $criteres['numeroAppel'] = $numeroAppel;
                if($form->has('numeroAppel')){
                    $form->get('numeroAppel')->setData($numeroAppel);
                }
            }

            $nom = $request->query->get('nom', null);
            if(!is_null($nom)){
                $nom = trim(urldecode($nom));
                $criteres['nom'] = $nom;
                if($form->has('nom')){
                    $form->get('nom')->setData($nom);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($region != null) ? 'Région="'.$region->getNom().'", ' : '';
                $pdfCriteres .= ($localite != null) ? 'Localité="'.$localite->getNom().'", ' : '';
                $pdfCriteres .= ($initStatus!=null && array_key_exists($initStatus, Comite::getInitWordArrayAssoc()))
                    ? 'Statut="'.Comite::getInitWordArrayAssoc()[$initStatus].'", ' : '';
                $pdfCriteres .= ($codeInit != null) ? 'C. initialisation="'.$codeInit.'", ' : '';
                $pdfCriteres .= ($numeroAppel != null) ? 'N° appel="'.$numeroAppel.'", ' : '';
                $pdfCriteres .= ($nom != null) ? 'Nom="'.$nom.'", ' : '';
            }
        }

        if(!is_null($request->query->get('pdf', null))) {
            return $this->pdfExport($criteres, $pdfCriteres);
        }
        $comites = $em->getRepository('SofieExpBundle:Comite')->getByCriteres($criteres, $offset, $page);

        return $this->render('SofieExpBundle:Comite:index.html.twig', array(
            'comites'=>$comites,
            "page"=>$page,
            'form'=>($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $comites = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Comite')
            ->getTotalByCriteres($criteres);
        $view = $this->renderView('SofieExpBundle:Comite:export/list.pdf.twig', array(
            'comites'=>$comites, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (
            $request->query->get('region', null) || $request->query->get('localite', null)
            || !is_null($request->query->get('initStatus', null)) || !is_null($request->query->get('codeInit', null))
            || !is_null($request->query->get('numeroAppel', null)) || !is_null($request->query->get('nom', null))
        );
    }

    protected function critereForm(Region $region = null)
    {
        $builder = $this->createFormBuilder()
            ->add('initStatus', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Status',
                'choices'=>Comite::getInitWordArrayAssoc(),
                'placeholder'=>'',
                'empty_data'=>null
            ))
            ->add('codeInit', 'text', array('required'=>false, 'trim'=>true, 'label'=>'C. initialisation'))
            ->add('numeroAppel', 'text', array('required'=>false, 'trim'=>true, 'label'=>'N° appel'))
            ->add('nom', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom'))
        ;
        if(is_null(self::$site)){
            $builder->add('region', 'entity', array(
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
        $builder->add('localite', 'entity', array(
            'required'=>false, 'trim'=>true, 'label'=>'Localité',
            'class'=>'SofieExpBundle:Localite',
            'choice_label'=>'nom',
            'placeholder'=>'',
            'empty_data'=>null,
            'query_builder'=>function(EntityRepository $er) use ($region){
                return $er->getByRegionBuilder($region);
            }
        ));
        return $builder->getForm();
    }

    /**
     * @Security("has_role('ROLE_ADD_COMITE')")
     */
    public function addAction(Request $request)
    {
        $comite = new Comite();
        $form = $this->createForm('sofie_expbundle_comite', $comite);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($comite, 'Comite', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieexp_comite_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:Comite:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_COMITE')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $comite = $em->getRepository('SofieExpBundle:Comite')->get(intval($id));
        if(empty($comite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_expbundle_comite', $comite);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->persist($comite);
                $em->flush();
                $this->logComite($comite, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieexp_comite_index'));
            }
        }
        return $this->render('SofieExpBundle:Comite:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_COMITE')")
     */
    public function viewAction($id)
    {
        $comite = $this->getDoctrine()->getRepository('SofieExpBundle:Comite')->get(intval($id));
        if(empty($comite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logComite($comite, 'consulté');

        return $this->render('SofieExpBundle:Comite:view.html.twig', array('comite'=>$comite));
    }

    /**
     * @Security("has_role('ROLE_DELETE_COMITE')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $comite = $em->getRepository('SofieExpBundle:Comite')->get(intval($id));
        if(empty($comite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em->remove($comite);
        $em->flush();
        $this->logComite($comite, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_comite_index'));
    }

    protected function logComite(Comite $comite, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Comite '.$action;
        if(method_exists($comite, 'logString')){
            $logMsg .= ' - infos ['.$comite->logString().']';
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

    public function ajaxInitAction(Request $request, $id)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $comite = $em->getRepository('SofieExpBundle:Comite')->get(intval($id));
        $result = array('success'=>true, 'numero'=>'');
        if(empty($comite)){
            throw $this->createNotFoundException('Information introuvable');
        }
        if($comite->isInitializable()){
            if($comite->getInitStatus()){
                $comite->setInitStatus(false);
                if(!is_null($comite->getNumeroAppel())){
                    $em->remove($comite->getNumeroAppel());
                    $comite->setNumeroAppel(null);
                }
                $this->logComite($comite, 'Initialisation annulée');
            }else{
                $result['numero'] = $request->request->get('numero', null);
                if(!preg_match('/^(\d|\+)\d{7,14}[\s|,;-]*$/', strval($result['numero']))){
                    $result['numero'] = $request->query->get('numero', null);
                }
                $result['numero'] = strval($result['numero']);
                if(preg_match('/^(\d|\+)\d{7,14}[\s|,;-]*$/', strval($result['numero']))){
                    $numeroAppel = new NumeroAppel();
                    $numeroAppel->setNumero($result['numero']);
                    $numeroAppel->setProfile($em->getRepository('SofieExpBundle:Profile')->get(intval(Profile::PROFILE_COMITE)));
                    $comite->setNumeroAppel($numeroAppel);
                    $comite->setInitStatus(true);
                    $this->logComite($comite, 'Initialisé');
                }else{
                    $result['success'] = false;
                    $result['message'] = 'Numéro de téléphone invalid.';
                    $this->logComite($comite, 'Initialisation échouée, cause:{'.$result['message'].'}');
                }
            }
            $result['initialize'] = $comite->getInitStatus();
            $em->flush();
        }else{
            $result['success'] = false;
            $result['message'] = $comite->titleInitialize();
            $this->logComite($comite, 'Initialisation échouée, cause:{'.$result['message'].'}');
        }
        return new Response(json_encode($result));
    }

    public function ajaxByLocaliteAction($id)
    {
        $localite = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Localite')->find($id);
        $text = '<option value=""></option>';
        if(!($localite instanceof Localite)){
            return new Response($text);
        }

        $comites = $localite->getComites();
        foreach($comites as $comite){
            if($comite->getOuvrage() == null || $comite){
                $text .= '<option value="'.$comite->getId().'">'.$comite->getNom().'</option>';
            }
        }
        return new Response($text);
    }

    public function ajaxByLocaliteAndFreeAction($id, $current=0)
    {
        $comites = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Comite')
            ->getByLocaliteIdAndFree($id, $current);
        $text = '<option value=""></option>';
        foreach($comites as $comite){
            if($comite->getOuvrage() == null || $comite){
                $text .= '<option value="'.$comite->getId().'">'.$comite->getNom().'</option>';
            }
        }
        return new Response($text);
    }
}
