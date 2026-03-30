<?php

namespace Sofie\ExpBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuiviPhysicoChimiqueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                'required'=>false, 'trim'=>true, 'label'=>'Date',
                'widget'=>'single_text', 'format'=>'dd/MM/yyyy',
                'attr'=>array('class'=>'date-picker form-control')
            ))
            ->add('ph', 'text', array('required'=>false, 'trim'=>true, 'label'=>'PH'))
            ->add('cond', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Cond.'))
            ->add('resSec', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Rés. sec'))
            ->add('ca', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Ca++'))
            ->add('mg', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Mg++'))
            ->add('na', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Na+'))
            ->add('k', 'text', array('required'=>false, 'trim'=>true, 'label'=>'K+'))
            ->add('cl', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Cl-'))
            ->add('no2', 'text', array('required'=>false, 'trim'=>true, 'label'=>'NO2'))
            ->add('no3', 'text', array('required'=>false, 'trim'=>true, 'label'=>'NO3-'))
            ->add('so4', 'text', array('required'=>false, 'trim'=>true, 'label'=>'SO4'))
            ->add('hco3', 'text', array('required'=>false, 'trim'=>true, 'label'=>'HCO3'))
            ->add('feTot', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Fe tot'))
            ->add('f', 'text', array('required'=>false, 'trim'=>true, 'label'=>'F'))
            ->add('as', 'text', array('required'=>false, 'trim'=>true, 'label'=>'As'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\SuiviPhysicoChimique',
            'intention' => 'sofie_expbundle_suiviphysicochimique_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_suiviphysicochimique';
    }
}
