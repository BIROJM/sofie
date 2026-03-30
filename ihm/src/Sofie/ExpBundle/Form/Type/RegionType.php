<?php

namespace Sofie\ExpBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text', array('trim'=>true, 'required'=>true, 'label'=>'Nom'))
            ->add('superficie', 'number', array('required'=>false, 'trim'=>true, 'label'=>'Superficie'))
            ->add('situation', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Situation'))
            ->add('longitude', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Longitude'))
            ->add('latitude', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Latitude'))
            ->add('carte', 'text', array('required'=>true, 'trim'=>true, 'label'=>'Carte'))
            ->add('zoom', 'integer', array('required'=>true, 'trim'=>true, 'label'=>'Zoom'))
            ->add('maxZoom', 'integer', array('required'=>true, 'trim'=>true, 'label'=>'Max zoom'))
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){
            $data =$event->getData();
            if(!$data || $data->getId()===null){
                $directeursBuilder = function(EntityRepository $er){
                    return $er->getDirecteursBuilder();
                };
                $sociologuesBuilder = function(EntityRepository $er){
                    return $er->getSociologuesBuilder();
                };
            }else{
                $directeursBuilder = function(EntityRepository $er) use ($data){
                    return $er->getDirecteursBuilder($data);
                };
                $sociologuesBuilder = function(EntityRepository $er) use ($data){
                    return $er->getSociologuesBuilder($data);
                };
            }
            $event->getForm()->add('iddr', 'entity', array(
                'required' => true, 'trim' => true, 'label' => 'Directeur',
                'class' => 'SofieExpBundle:Agent',
                'placeholder' => '',
                'empty_data' => null,
                'query_builder' => $directeursBuilder
            ))->add('sociologue', 'entity', array(
                'required' => true, 'trim' => true,
                'class' => 'SofieExpBundle:Agent',
                'placeholder' => '',
                'empty_data' => null,
                'query_builder' => $sociologuesBuilder
            ))
            ;
        });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Region',
            'intention' => 'sofie_expbundle_region_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_region';
    }
}
