<?php

namespace Sofie\ExpBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VenuEauPrincipaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profondeur', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Profondeur'))
            ->add('debitCumule', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Débit cumulé'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\VenuEauPrincipale',
            'intention' => 'sofie_expbundle_venueauprincipale_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_venueauprincipale';
    }
}
