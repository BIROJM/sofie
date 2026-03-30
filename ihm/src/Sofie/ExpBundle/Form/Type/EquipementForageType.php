<?php

namespace Sofie\ExpBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipementForageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nature', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Nature'))
            ->add('profSup', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Prof. sup. (de)'))
            ->add('profInf', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Prof. inf. (à)'))
            ->add('diametre', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Diamètre'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\EquipementForage',
            'intention' => 'sofie_expbundle_equipementforage_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_equipementforage';
    }
}
