<?php

namespace Sofie\UserBundle\Controller;

use Sofie\AdminBundle\Model\ChangePassword;
use Sofie\AdminBundle\Model\Logging;
use Sofie\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SofieUserBundle:User')->get($user->getId());
        if(!$user || $user == null){
            throw $this->createNotFoundException('Information non trouvée !');
        }
        $changePassword = new ChangePassword;
        $form = $this->createForm('sofie_adminbundle_changepassword', $changePassword);
        if($request->getMethod() == Request::METHOD_POST){
            $form->handleRequest($request);
            if($form->isValid()){
                $user->setPlainPassword($changePassword->getNewPassword());
                if($user->getPlainPassword()){
                    $encoder = $this->get('sofie.password_encoder');
                    $user = $encoder->encodeUserPassword($user, $this);
                }
                $em->flush();
                $logMsg = '['.$user->getUsername().'] Mot de passe modifié';
                Logging::write($logMsg);
                $this->get('ras_flash_alert.alert_reporter')->addSuccess("Succès !");
                return $this->redirect($this->generateUrl('sofieuser_user_index'));
            }
        }
        return $this->render('SofieUserBundle:Profile:index.html.twig', array(
            'user'=>$user,
            'form'=>$form->createView()
        ));
    }

}
