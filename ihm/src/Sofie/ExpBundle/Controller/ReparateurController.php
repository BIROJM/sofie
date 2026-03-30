<?php

namespace Sofie\ExpBundle\Controller;

use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\NumeroAppel;
use Sofie\ExpBundle\Entity\Profile;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\ExpBundle\Entity\Reparateur;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

class ReparateurController extends Controller
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

        if($form && $this->isCritere($request)){
            $region = $em->getRepository('SofieExpBundle:Region')->find(intval(urldecode($request->query->get('region', null))));
            if(!is_null($region)){
                $criteres['region'] = $region;
                if($form->has('region')){
                    $form->get('region')->setData($region);
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

            $prenoms = $request->query->get('prenoms', null);
            if(!is_null($prenoms)){
                $prenoms = trim(urldecode($prenoms));
                $criteres['prenoms'] = $prenoms;
                if($form->has('prenoms')){
                    $form->get('prenoms')->setData($prenoms);
                }
            }

            if(!is_null($request->query->get('pdf', null))) {
                $pdfCriteres .= ($region != null) ? 'Région="'.$region->getNom().'", ' : '';
                $pdfCriteres .= ($initStatus!=null && array_key_exists($initStatus, Reparateur::getInitWordArrayAssoc()))
                    ? 'Statut="'.Reparateur::getInitWordArrayAssoc()[$initStatus].'", ' : '';
                $pdfCriteres .= ($codeInit != null) ? 'C. initialisation="'.$codeInit.'", ' : '';
                $pdfCriteres .= ($numeroAppel != null) ? 'N° appel="'.$numeroAppel.'", ' : '';
                $pdfCriteres .= ($nom != null) ? 'Nom="'.$nom.'", ' : '';
                $pdfCriteres .= ($prenoms != null) ? 'Prénoms="'.$prenoms.'", ' : '';
            }
        }

        if(!is_null($request->query->get('pdf', null))){
            return $this->pdfExport($criteres, $pdfCriteres);
        }

        $reparateurs = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Reparateur')
            ->getByCriteres($criteres, $offset, $page);

        return $this->render('SofieExpBundle:Reparateur:index.html.twig', array(
            'reparateurs'=>$reparateurs,
            "page"=>$page,
            'form' => ($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $reparateurs = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Reparateur')
            ->getTotalByCriteres($criteres);
        $view = $this->renderView('SofieExpBundle:Reparateur:export/list.pdf.twig', array(
            'reparateurs'=>$reparateurs, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (
            $request->query->get('region', null) || !is_null($request->query->get('initStatus', null))
            || !is_null($request->query->get('codeInit', null)) || !is_null($request->query->get('numeroAppel', null))
            || !is_null($request->query->get('nom', null)) || !is_null($request->query->get('prenoms', null))
        );
    }

    protected function critereForm()
    {
        $builder = $this->createFormBuilder()
            ->add('initStatus', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Status',
                'choices'=>Reparateur::getInitWordArrayAssoc(),
                'placeholder'=>'',
                'empty_data'=>null
            ))
            ->add('codeInit', 'text', array('required'=>false, 'trim'=>true, 'label'=>'C. initialisation'))
            ->add('numeroAppel', 'text', array('required'=>false, 'trim'=>true, 'label'=>'N° appel'))
            ->add('nom', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom'))
            ->add('prenoms', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Prénoms'))
        ;
        if(is_null(self::$site)){
            $builder->add('region', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label'=>'nom',
                'placeholder'=>'',
                'empty_data'=>null
            ));
        }
        return $builder->getForm();
    }

    /**
     * @Security("has_role('ROLE_ADD_REPARATEUR')")
     */
    public function addAction(Request $request)
    {
        $reparateur = new Reparateur();
        $form = $this->createForm('sofie_expbundle_reparateur', $reparateur);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($reparateur, 'Reparateur', $this->getUser()->getUsername())){
					$numero = $form->get('numero')->getData();
					$id = $reparateur->getId();					
					$this->ajaxInitAction($request, $id, $numero);
                    return $this->redirect($this->generateUrl('sofieexp_reparateur_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:Reparateur:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_REPARATEUR')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $reparateur = $em->getRepository('SofieExpBundle:Reparateur')->get(intval($id));
        if(empty($reparateur)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_expbundle_reparateur', $reparateur);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                if($reparateur->detachNumeroAppel()){
                    $reparateur->setNumeroAppel(null);
                }
                $em->persist($reparateur);
                if($reparateur->removeNumeroAppel()){
                    $em->remove($reparateur->getNumeroAppel());
                }
                $em->flush();
                $this->logReparateur($reparateur, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieexp_reparateur_index'));
            }
        }
        return $this->render('SofieExpBundle:Reparateur:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_REPARATEUR')")
     */
    public function viewAction($id)
    {
        $reparateur = $this->getDoctrine()->getRepository('SofieExpBundle:Reparateur')->get(intval($id));
        if(empty($reparateur)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logReparateur($reparateur, 'consulté');

        return $this->render('SofieExpBundle:Reparateur:view.html.twig', array('reparateur'=>$reparateur));
    }

    /**
     * @Security("has_role('ROLE_DELETE_REPARATEUR')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $reparateur = $em->getRepository('SofieExpBundle:Reparateur')->get(intval($id));
        if(empty($reparateur)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em->remove($reparateur);
        $em->flush();
        $this->logReparateur($reparateur, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_reparateur_index'));
    }

    protected function logReparateur(Reparateur $reparateur, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Reparateur '.$action;
        if(method_exists($reparateur, 'logString')){
            $logMsg .= ' - infos ['.$reparateur->logString().']';
        }
        Logging::write($logMsg);
    }

    /*
     * AJAx REQUEST
     * */
    public function ajaxInitAction(Request $request, $id, $numero = null)
    {
        if(!is_null(self::$site)){
            throw $this->createAccessDeniedException('Accès réfusé !');
        }
        $em = $this->getDoctrine()->getManager();
        $reparateur = $em->getRepository('SofieExpBundle:Reparateur')->get(intval($id));
        $result = array('success'=>true, 'numero'=>'');
        if(empty($reparateur)){
            throw $this->createNotFoundException('Information introuvable');
        }
        if($reparateur->isInitializable()){
            if($reparateur->getInitStatus()){
                $reparateur->setInitStatus(false);
                if(!is_null($reparateur->getNumeroAppel())){
                    $em->remove($reparateur->getNumeroAppel());
                    $reparateur->setNumeroAppel(null);
                }
                $this->logReparateur($reparateur, 'Initialisation annulée');
            }else{
				if(empty($numero))
				{
					$result['numero'] = $request->request->get('numero', null);
					if(!preg_match('/^(\d|\+)\d{7,14}[\s|,;-]*$/', strval($result['numero']))){
						$result['numero'] = $request->query->get('numero', null);
					}
					$result['numero'] = strval($result['numero']);
				}
				else
				{
					$result['numero'] = $numero;
				}                
                if(preg_match('/^(\d|\+)\d{7,14}[\s|,;-]*$/', strval($result['numero']))){
                    $numeroAppel = new NumeroAppel();
                    $numeroAppel->setNumero($result['numero']);
                    $numeroAppel->setProfile($em->getRepository('SofieExpBundle:Profile')->get(intval(Profile::PROFILE_REPARATEUR)));
                    $reparateur->setNumeroAppel($numeroAppel);
                    $reparateur->setInitStatus(true);
                    $this->logReparateur($reparateur, 'Initialisé');
                }else{
                    $result['success'] = false;
                    $result['message'] = 'Numéro de téléphone invalid.';
                    $this->logReparateur($reparateur, 'Initialisation échouée, cause:{'.$result['message'].'}');
                }
            }
            $result['initialize'] = $reparateur->getInitStatus();
            $em->flush();
        }else{
            $result['success'] = false;
            $result['message'] = $reparateur->titleInitialize();
            $this->logReparateur($reparateur, 'Initialisation échouée, cause:{'.$result['message'].'}');
        }
        return new Response(json_encode($result));
    }
}
