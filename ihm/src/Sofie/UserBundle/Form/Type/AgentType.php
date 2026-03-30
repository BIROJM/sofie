<?php

namespace Sofie\UserBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Sofie\AdminBundle\Model\ParameterFile;
use Doctrine\ORM\EntityRepository;
use Sofie\ExpBundle\Form\EventListener\AddNumeroAppelSubscriber;
use Sofie\ExpBundle\Form\EventListener\AgentSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AgentType extends AbstractType
{
    protected $site;
    protected $em;
    protected $authorizationChecker;

    public function __construct(EntityManager $em, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->em = $em;
        $this->site = ParameterFile::loadSite();
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $authorizationChecker = $this->authorizationChecker;
        $builder
            ->add('nom', 'text', array('trim'=>true, 'required'=>true))
			//->add('numero', 'text', array('trim'=>true, 'required'=>true))
            ->add('prenoms', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Prénoms'))
            ->add('qualification', 'entity', array(
                'required' => true, 'trim' => true,
                'class'=>'SofieExpBundle:Profile',
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er) use ($authorizationChecker){
                    return ($authorizationChecker->isGranted('ROLE_ADMIN')) ?
                        $er->getForAdminActionBuilder() : $er->getForUserActionBuilder()
                    ;
                },
                'empty_data'=>null
            ))
        ;
        $regionRequired = !is_null($this->site);
        $builder->add('region', 'entity', array(
            'required'=>$regionRequired, 'trim'=>true, 'label'=>'Région',
            'class'=>'SofieExpBundle:Region',
            'choice_label' => 'nom',
            'placeholder' => '',
            'empty_data'=>null,
            'query_builder'=>function(EntityRepository $er){
                return $er->getByUserRegionBuilder();
            }
        ));

        $builder->addEventSubscriber(new AgentSubscriber($this->em, $this->site));
        /*if($this->authorizationChecker->isGranted("ROLE_ADMIN")){
            $builder->addEventSubscriber(new AddNumeroAppelSubscriber());
        }*/
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Agent',
            'intention' => 'sofie_expbundle_agent_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_userbundle_agent';
    }
}
