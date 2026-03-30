<?php

namespace Sofie\ExpBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EssaisPompageType extends AbstractType
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
                'attr'=>array('class'=>'form-control date-picker')
            ))
            ->add('typeEssai', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Type essai'))
            ->add('dureeEssai', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Durée essai'))
            ->add('debitMax', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Débit max'))
            ->add('rabattement', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Rabattement'))
            ->add('debitCritique', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Débit critique'))
            ->add('transmissivite', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Transmissivité'))
            ->add('emmagasinage', 'text', array('required'=>false, 'trim'=>true, 'label'=>'Emmagasinage'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\EssaisPompage',
            'intention' => 'sofie_expbundle_essaispompage_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_expbundle_essaispompage';
    }
}
