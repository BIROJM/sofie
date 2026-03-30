<?php

namespace Sofie\UserBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Model\ParameterFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserType extends AbstractType
{
    protected $site;
    protected $em;
    protected $authorizationChecker;

    public function __construct(EntityManager $em, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->site = ParameterFile::loadSite();
        $this->em = $em;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Nom d\'utilisateur'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password', 'required'=>false, 'trim'=>true,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'first_options'  => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Répéter mot de passe')
            ))
            ->add('email', 'email', array('required' => false, 'trim' => true, 'label'=>'Email'))
            ->add('isMobile', 'checkbox', array('required' => false, 'trim' => true, 'label'=>'Mobile'))
        ;

        if($this->authorizationChecker->isGranted('ROLE_ACTIVATE_USER')){
            $builder->add('isActive', 'checkbox', array('required' => false, 'trim' => true, 'label'=>'Actif'));
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\UserBundle\Entity\User',
            'intention' => 'sofie_userbundle_user_type',
            'cascade_validation' => true
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_userbundle_user';
    }
}
