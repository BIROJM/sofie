<?php

namespace Sofie\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('currentPassword', 'password', array("required"=>true, "trim"=>true, "label"=>"Mot de passe actuel "))
            ->add('newPassword', 'repeated', array(
							"type"=>"password", "required"=>true,
							"first_options"=>array("label"=>"Nouveau mot de passe "),
							"second_options"=>array("label"=>"Confirmation "),
							"invalid_message"=>"Les champs du nouveau mot de passe doivent correspondre.",
						))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\AdminBundle\Model\ChangePassword',
            'intention' => 'sofie_adminbundle_changepassword_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_adminbundle_changepassword';
    }
}
