<?php

namespace Sofie\ExpBundle\Form\Type;

use Sofie\ExpBundle\Form\EventListener\AddNumeroAppelSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ReparateurType extends AbstractType
{
    protected $authorizationChecker;

    /**
     * NumeroAppelType constructor.
     * @param $authorizationChecker
     */
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('trim'=>true, 'required'=>true, 'label'=>'Nom'))
            ->add('prenoms', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Prénoms'))
			//->add('numero', 'text', array('trim'=>true, 'required'=>true, 'mapped' => false))
        ;

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
            'data_class' => 'Sofie\ExpBundle\Entity\Reparateur',
            'intention' => 'sofie_expbundle_reparateur_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_reparateur';
    }
}
