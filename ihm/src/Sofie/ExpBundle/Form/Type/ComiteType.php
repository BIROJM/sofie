<?php

namespace Sofie\ExpBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\Form\EventListener\AddLocaliteSubscriber;
use Sofie\ExpBundle\Form\EventListener\AddNumeroAppelSubscriber;
use Sofie\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ComiteType extends AbstractType
{

    private $em;
    private $site;
    private $authorizationChecker;

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
        $builder
            ->add('nom', 'text', array('trim'=>true))
            ->add('nomSecretaire', 'text', array('required'=>false, 'trim'=>false, 'label'=>'Nom sécrétaire'))
            ->add('prenomsSecretaire', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Prénoms sécrétaire'))
        ;

        if(is_null($this->site)){
            $builder->add('region', 'entity', array(
                'required'=>true, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label' => 'nom',
                'empty_data' => null,
                'placeholder' => '',
                'query_builder' => function(EntityRepository $er){
                    return $er->getByUserRegionBuilder();
                },
                'mapped'=>false
            ));
        }

        $builder->addEventSubscriber(new AddLocaliteSubscriber($builder, $this->em, $this->site));
        if($this->authorizationChecker->isGranted("ROLE_ADMIN")){
            $builder->addEventSubscriber(new AddNumeroAppelSubscriber());
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Comite',
            'intention' => 'sofie_expbundle_comite_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_comite';
    }
}
