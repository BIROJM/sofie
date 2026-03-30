<?php

namespace Sofie\UserBundle\Controller;

use Sofie\UserBundle\Entity\User;
use Sofie\UserBundle\Entity\UserGroupe;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;

class UserController extends Controller
{
    protected function extractUserGroupes(Form $form, User &$user)
    {
        if($form->has('groupes')){
            $em = $this->getDoctrine()->getManager();
            $groupesId = array();
            $em->getFilters()->disable('softdeleteable');
            foreach($form->get('groupes')->getData() as $groupe){
                $userGroupe = new UserGroupe();
                $userGroupe->setGroupe($groupe);
                $userGroupe->setUser($user);
                $userGroupeOld = $em->getRepository('SofieUserBundle:UserGroupe')
                    ->get($userGroupe->getUser(), $userGroupe->getGroupe())
                ;
                if($userGroupeOld){
                    $userGroupeOld->setDeletedAt(null);
                    $user->addUserGroupe($userGroupeOld);

                }else{
                    $user->addUserGroupe($userGroupe);
                }
                $groupesId[] = $groupe->getId();
            }
            $em->getFilters()->enable('softdeleteable');
            foreach($user->getUserGroupes() as $userGroupe){
                if(!in_array($userGroupe->getGroupe()->getId(), $groupesId)){
                    $user->removeUserGroupe($userGroupe);
                    $em->remove($userGroupe);
                }
            }
        }

    }
}
