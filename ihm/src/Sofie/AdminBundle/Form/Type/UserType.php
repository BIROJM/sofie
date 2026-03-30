<?php

namespace Sofie\AdminBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Form\EventListener\AddAgentSubscriber;
use Sofie\AdminBundle\Model\ParameterFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sofie\UserBundle\Form\Type\UserType as BaseUserType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserType extends BaseUserType
{
    public function __construct(EntityManager $em, AuthorizationCheckerInterface $authorizationChecker)
    {
        parent::__construct($em, $authorizationChecker);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('agent', 'sofie_userbundle_agent')
            ->add('groupes', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Groupes',
                'class'=>'SofieUserBundle:Groupe',
                'choice_label' => 'name',
                'multiple' => true,
                'empty_data'=>null,
                'mapped'=>false
            ))
        ;

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
            $builder->add('admin', 'checkbox', array('required' => false, 'trim' => true, 'label'=>'Administrateur'));
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event){
            $form = $event->getForm();
            if(!is_null($event->getData())){
                $userGroupes = $event->getData()->getUserGroupes();
                $goupes = new ArrayCollection();
                foreach($userGroupes as $userGroupe){
                    $goupes->add($userGroupe->getGroupe());
                }
                $form->get('groupes')->setData($goupes);
            }
        });

//        $builder->addEventSubscriber(new AddAgentSubscriber($builder, $this->em, $this->site));

    }
    
    /**
     * @param OptionsResolver $resolver
     */
    /*public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
    }*/

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_adminbundle_user';
    }
}
