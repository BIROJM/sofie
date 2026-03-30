<?php

namespace Sofie\ExpBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Controller\SessionInfo;
use Sofie\AdminBundle\Entity\Config;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\ExpBundle\Entity\NumeroAppel;
use Sofie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\ExpBundle\Entity\Agent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sofie\UserBundle\Controller\UserController as BaseUserController;

class AgentController extends BaseUserController
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * @Security("has_role('ROLE_AGENT')")
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

            $qualification = $em->getRepository('SofieExpBundle:Profile')
                ->find(intval(urldecode($request->query->get('qualification', null))));
            if(!is_null($qualification)){
                $criteres['qualification'] = $qualification;
                if($form->has('qualification')){
                    $form->get('qualification')->setData($qualification);
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
                $pdfCriteres .= ($qualification != null) ? 'Qualification="'.$qualification->getDesignation().'", ' : '';
                $pdfCriteres .= ($initStatus!=null && array_key_exists($initStatus, Agent::getInitWordArrayAssoc()))
                    ? 'Statut="'.Agent::getInitWordArrayAssoc()[$initStatus].'", ' : '';
                $pdfCriteres .= ($codeInit != null) ? 'C. initialisation="'.$codeInit.'", ' : '';
                $pdfCriteres .= ($numeroAppel != null) ? 'N° appel="'.$numeroAppel.'", ' : '';
                $pdfCriteres .= ($nom != null) ? 'Nom="'.$nom.'", ' : '';
                $pdfCriteres .= ($prenoms != null) ? 'Prénoms="'.$prenoms.'", ' : '';
            }
        }

        if(!is_null($request->query->get('pdf', null))){
            return $this->pdfExport($criteres, $pdfCriteres);
        }

        $agents = $em->getRepository('SofieExpBundle:Agent')->getByCriteres($criteres, $offset, $page);

//        $paginator  = $this->get('knp_paginator');
//        $agents = $paginator->paginate($query, $page, $offset);

        return $this->render('SofieExpBundle:Agent:index.html.twig', array(
            'agents'=>$agents,
            "page"=>$page,
            'form' => ($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $agents = $this->getDoctrine()->getManager()->getRepository('SofieExpBundle:Agent')
            ->getTotalByCriteres($criteres);
        $view = $this->renderView('SofieExpBundle:Agent:export/list.pdf.twig', array(
            'agents'=>$agents, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (
            $request->query->get('region', null) || $request->query->get('qualification', null)
            || !is_null($request->query->get('initStatus', null)) || !is_null($request->query->get('codeInit', null))
            || !is_null($request->query->get('numeroAppel', null)) || !is_null($request->query->get('nom', null))
            || !is_null($request->query->get('prenoms', null))
        );
    }

    protected function critereForm()
    {
        $builder = $this->createFormBuilder()
            ->add('qualification', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Qualification',
                'class'=>'SofieExpBundle:Profile',
                'choice_label'=>'designation',
                'placeholder'=>'',
                'empty_data'=>null,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getForUserActionBuilder();
                }
            ))
            ->add('initStatus', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Status',
                'choices'=>Agent::getInitWordArrayAssoc(),
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
     * @Security("has_role('ROLE_ADD_AGENT')")
     */
    public function addAction(Request $request)
    {
        $agent = new Agent();
        $form = $this->createForm('sofie_expbundle_agent', $agent);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                if(!is_null($agent->getUser()) && $agent->getUser()->getPlainPassword()){
                    $encoder = $this->get('sofie.password_encoder');
                    $user = $agent->getUser();
                    $user = $encoder->encodeUserPassword($user, $this);
                    $this->extractUserGroupes($form, $user);
                    $agent->setUser($user);
                }
				//dump($request->request->all());
				//echo 'ert' . $request->request->get('prenoms');
				
				$apiAdd = $this->get('api_add');
                if($apiAdd->proccess($agent, 'Agent', $this->getUser()->getUsername())){					
					$numero = $form->get('numero')->getData();
					$id = $agent->getId();					
					$this->ajaxInitAction($request, $id, $numero);
					//echo $id; exit;
                    return $this->redirect($this->generateUrl('sofieexp_agent_index'));
                }
            }
        }
        return $this->render('SofieExpBundle:Agent:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_AGENT')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $agent = $em->getRepository('SofieExpBundle:Agent')->get(intval($id));
        if(empty($agent)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_expbundle_agent', $agent);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                if(!is_null($agent->getUser()) && $agent->getUser()->getPlainPassword()){
                    $encoder = $this->get('sofie.password_encoder');
                    $user = $agent->getUser();
                    $user = $encoder->encodeUserPassword($user, $this);
                    $this->extractUserGroupes($form, $user);
                    $agent->setUser($user);
                }
                $em->persist($agent);
                $em->flush();
                $this->logAgent($agent, 'modifié');
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieexp_agent_index'));
            }
        }
        return $this->render('SofieExpBundle:Agent:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_AGENT')")
     */
    public function viewAction($id)
    {
        $agent = $this->getDoctrine()->getRepository('SofieExpBundle:Agent')->get(intval($id));
        if(empty($agent)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logAgent($agent, 'consulté');

        return $this->render('SofieExpBundle:Agent:view.html.twig', array('agent'=>$agent));
    }

    /**
     * @Security("has_role('ROLE_DELETE_AGENT')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $agent = $em->getRepository('SofieExpBundle:Agent')->get(intval($id));
        if(empty($agent)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em->remove($agent);
        $em->flush();
        $this->logAgent($agent, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieexp_agent_index'));
    }

    protected function logAgent(Agent $agent, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] Agent '.$action;
        if(method_exists($agent, 'logString')){
            $logMsg .= ' - infos ['.$agent->logString().']';
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
        $agent = $em->getRepository('SofieExpBundle:Agent')->get(intval($id));
        if(empty($agent)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $result = array('success'=>true, 'numero'=>'');
        if($agent->isInitializable()){
            if($agent->getInitStatus()){
                $agent->setInitStatus(false);
                if(!is_null($agent->getNumeroAppel())){
                    $em->remove($agent->getNumeroAppel());
                    $agent->setNumeroAppel(null);
                    $agent->setIdNumappelNotification(null);
                }
                $this->logAgent($agent, 'Initialisation annulée');
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
                    $numeroAppel->setProfile($agent->getQualification());
                    $em->persist($numeroAppel);
                    $em->flush();
                    $agent->setNumeroAppel($numeroAppel);
                    $agent->setIdNumappelNotification($numeroAppel->getId());
                    $agent->setInitStatus(true);
                    $this->logAgent($agent, 'Initialisé');
                }else{
                    $result['success'] = false;
                    $result['message'] = 'Numéro de téléphone invalid.';
                    $this->logAgent($agent, 'Initialisation échouée, cause:{'.$result['message'].'}');
                }
            }
            $result['initialize'] = $agent->getInitStatus();
            $em->flush();
        }else{
            $result['success'] = false;
            $result['message'] = $agent->titleInitialize();
            $this->logAgent($agent, 'Initialisation échouée, cause:{'.$result['message'].'}');
        }
        return new Response(json_encode($result));
    }

    public function ajaxFormenByRegionAction($idRegion)
    {
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('SofieExpBundle:Region')->find(intval($idRegion, 10));
        $agentsFormen = $em->getRepository('SofieExpBundle:Agent')->getAgentsFormenByRegion($region);
        $result = '<option></option>';
        if(is_array($agentsFormen)){
            foreach($agentsFormen as $agent){
                $result .= '<option value="'.$agent->getId().'">'.$agent.'</option>';
            }
        }

        return new Response($result);
    }

    public function ajaxByRegionNotUserAction($idRegion)
    {
        $em = $this->getDoctrine()->getManager();
        $region = $em->getRepository('SofieExpBundle:Region')->find(intval($idRegion, 10));
        $agents = $em->getRepository('SofieExpBundle:Agent')->getByRegionNotUser($region);
        $result = '<option></option>';
        if(is_array($agents)){
            foreach($agents as $agent){
                $result .= '<option value="'.$agent->getId().'">'.$agent.'</option>';
            }
        }

        return new Response($result);
    }
}
