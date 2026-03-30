<?php

namespace Sofie\AdminBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Model\Logging;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\AdminBundle\Model\Stati;
use Sofie\UserBundle\Entity\User;
use Sofie\UserBundle\Entity\UserGroupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sofie\UserBundle\Controller\UserController as BaseUserController;

class UserController extends BaseUserController
{
    protected static $site;

    public function __construct()
    {
        self::$site = ParameterFile::loadSite();
    }

    /**
     * @Security("has_role('ROLE_USER_VIEW')")
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
            $region = $em->getRepository('SofieExpBundle:Region')->get(intval(urldecode($request->query->get('region', null))));
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

            $status = $request->query->get('status', null);
            if(!is_null($status)){
                $status = trim(urldecode($status));
                $criteres['status'] = $status;
                if($form->has('status')){
                    $form->get('status')->setData($status);
                }
            }

            $email = $request->query->get('email', null);
            if(!is_null($email)){
                $email = trim(urldecode($email));
                $criteres['email'] = $email;
                if($form->has('email')){
                    $form->get('email')->setData($email);
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

            if(!is_null($request->query->get('pdf', null))){
                $pdfCriteres .= ($region!=null) ? 'Région="'.$region->getNom().'", ' : '';
                $pdfCriteres .= ($qualification != null) ? 'Qualification="'.$qualification->getDesignation().'", ' : '';
                $pdfCriteres .= ($status!=null && array_key_exists($status, User::getActiveStrArrayAssoc()))
                    ? 'Statut="'.User::getActiveStrArrayAssoc()[$status].'", ' : '';
                $pdfCriteres .= ($email != null) ? 'Email="'.$email.'", ' : '';
                $pdfCriteres .= ($nom != null) ? 'Nom="'.$nom.'", ' : '';
                $pdfCriteres .= ($prenoms != null) ? 'Prénoms="'.$prenoms.'", ' : '';
            }
        }
        if(!is_null($request->query->get('pdf', null))){
            return self::pdfExport($criteres, $pdfCriteres);
        }
        $users = $em->getRepository('SofieUserBundle:User')->getByCriteres($criteres, $offset, $page);
        return $this->render('SofieAdminBundle:User:index.html.twig', array(
            'users'=>$users,
            "page"=>$page,
            'form' => ($form)?$form->createView():null
        ));
    }

    public function pdfExport(array $criteres=array(), $pdfCriteres='')
    {
        $users = $this->getDoctrine()->getManager()->getRepository('SofieUserBundle:User')
            ->getFullByCriteres($criteres);
        $view = $this->renderView('SofieAdminBundle:User:export/list.pdf.twig', array(
            'users'=>$users, 'criteres'=>rtrim(trim($pdfCriteres), ';,')
        ));
        return Stati::exportToPdf($this->get('tfox.mpdfport'), $view);
    }

    protected function isCritere(Request $request)
    {
        return (
            $request->query->get('region', null) || $request->query->get('qualification', null)
            || !is_null($request->query->get('status', null)) || !is_null($request->query->get('email', null))
            || !is_null($request->query->get('nom', null)) || !is_null($request->query->get('prenoms', null))
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
                    return $er->getForAdminActionBuilder();
                }
            ))
            ->add('status', 'choice', array(
                'required'=>false, 'trim'=>true, 'label'=>'Status',
                'choices'=>User::getActiveStrArrayAssoc(),
                'placeholder'=>'',
                'empty_data'=>null
            ))
            ->add('email', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Email'))
            ->add('nom', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nom'))
            ->add('prenoms', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Prénoms'))
        ;
        if(is_null(self::$site)) {
            $builder->add('region', 'entity', array(
                'required' => false, 'trim' => true, 'label' => 'Région',
                'class' => 'SofieExpBundle:Region',
                'choice_label' => 'nom',
                'placeholder' => '',
                'empty_data' => null
            ));
        }
        return $builder->getForm();
    }

    /**
     * @Security("has_role('ROLE_ADD_USER')")
     */
    public function addAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('sofie_adminbundle_user', $user);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                if($user->getPlainPassword()){
                    $encoder = $this->get('sofie.password_encoder');
                    $user = $encoder->encodeUserPassword($user, $this);
                }
                $this->extractUserGroupes($form, $user);
                $apiAdd = $this->get('api_add');
                if($apiAdd->proccess($user, 'User', $this->getUser()->getUsername())){
                    return $this->redirect($this->generateUrl('sofieadmin_user_index'));
                }
            }
        }
        return $this->render('SofieAdminBundle:User:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_EDIT_USER')")
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SofieUserBundle:User')->get(intval($id));
        if(empty($user)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $form = $this->createForm('sofie_adminbundle_user', $user);
        if($request->getMethod()==Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $em->getConnection()->beginTransaction();
                try{
                    if($user->getPlainPassword()){
                        $encoder = $this->get('sofie.password_encoder');
                        $user = $encoder->encodeUserPassword($user, $this);
                    }
                    $this->extractUserGroupes($form, $user);
                    $em->flush();
                    $em->commit();
                    $this->logUser($user, 'modifié');
                    $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                    return $this->redirect($this->generateUrl('sofieadmin_user_index'));
                } catch (\Exception $e) {
                    $em->getConnection()->rollback();
                    throw $e;
                }
            }
        }
        return $this->render('SofieAdminBundle:User:edit.html.twig', array('form'=>$form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER_VIEW')")
     */
    public function viewAction($id)
    {
        $user = $this->getDoctrine()->getRepository('SofieUserBundle:User')->get(intval($id));
        if(empty($user)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $this->logUser($user, 'consulté');

        return $this->render('SofieAdminBundle:User:view.html.twig', array('user'=>$user));
    }

    /**
     * @Security("has_role('ROLE_DELETE_USER')")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SofieUserBundle:User')->get(intval($id));
        if(empty($user)){
            throw $this->createNotFoundException('Information introuvable');
        }
        $em->remove($user);
        $em->flush();
        $this->logUser($user, 'supprimé');
        $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
        return $this->redirect($this->generateUrl('sofieadmin_user_index'));
    }

    protected function logUser(User $user, $action='')
    {
        $logMsg = '['.$this->getUser()->getUsername().'] User '.$action;
        if(method_exists($user, 'logString')){
            $logMsg .= ' - infos ['.$user->logString().']';
        }
        Logging::write($logMsg);
    }
}
