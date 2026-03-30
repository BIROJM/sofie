<?php

namespace Sofie\ExpBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\AdminBundle\Model\ParameterFile;
use Sofie\ExpBundle\Form\EventListener\AddAgentFormenByRegionSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocaliteType extends AbstractType
{
    protected $em;
    protected $site;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->site = ParameterFile::loadSite();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('trim'=>true, 'required'=>true, 'label'=>'Nom'))
            ->add('nombreHabitant', 'integer', array('required'=>false, 'trim'=>true, 'label'=>'Nombre d\'habitant'))
            ->add('reparateur', 'entity', array(
                'required'=>true, 'trim'=>true, 'label'=>'Réparateur',
                'class'=>'SofieExpBundle:Reparateur',
                'placeholder'=>'',
                'empty_data'=>null
            ))
        ;

        if(is_null($this->site)){
            $builder->add('region', 'entity', array(
                'required'=>true, 'trim'=>true, 'label'=>'Région',
                'class'=>'SofieExpBundle:Region',
                'choice_label'=>'nom',
                'placeholder'=>'',
                'empty_data'=>null,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getByUserRegionBuilder();
                }
            ));
        }

        $builder->addEventSubscriber(new AddAgentFormenByRegionSubscriber($builder, $this->em, $this->site));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Localite',
            'intention' => 'sofie_expbundle_localite_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_localite';
    }
}
