<?php

namespace Sofie\ExpBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Sofie\AdminBundle\Model\ParameterFile;
use Doctrine\ORM\EntityRepository;
use Sofie\ExpBundle\Form\EventListener\AgentSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sofie\UserBundle\Form\Type\AgentType as BaseAgentType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AgentType extends BaseAgentType
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
            ->add('user', 'sofie_userbundle_user')
            ->add('qualification', 'entity', array(
                'required' => true, 'trim' => true,
                'class'=>'SofieExpBundle:Profile',
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er){
                    return $er->getForUserActionBuilder();
                },
                'empty_data'=>null
            ))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_agent';
    }
}
