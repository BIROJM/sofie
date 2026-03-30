<?php

namespace Sofie\ExpBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoupeGeologiqueType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coteSup', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Cote sup'))
            ->add('coteInf', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Cote inf'))
            ->add('lithographie', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Lithologie'))
            ->add('description', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Description'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\CoupeGeologique',
            'intention' => 'sofie_expbundle_coupegeologique_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_coupegeologique';
    }
}
