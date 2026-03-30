<?php

namespace Sofie\AdminBundle\Form\Type;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('designation', 'text', array('trim'=>true, 'label'=>'Nom du profil'))
			 ->add('commentaire', 'textarea', array('trim'=>true, 'label'=>'Commentaire'))
            /*->add('users', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Utilisateurs',
                'class'=>'SofieUserBundle:User',
//                'choice_label'=>'username',
                'multiple' => true,
                'empty_data'=>null,
                'mapped'=>false,
                'query_builder'=>function(EntityRepository $er){
                    return $er->getByUserRegionBuilder();
                }
            ))*/
            /*->add('droits', 'entity', array(
                'required'=>false, 'trim'=>true, 'label'=>'Droits',
                'class'=>'SofieUserBundle:Droit',
                'choice_label' => 'libelle',
                'multiple' => true,
                'expanded' => true,
                'empty_data'=>null,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('d')->orderBy('d.context');
                },
                'mapped'=>false
            ))*/
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event){
            $form = $event->getForm();

            // Gestion des users
            /*if($form->has('users')){
                $userGroupes = $event->getData()->getUserGroupes();
                $users = new ArrayCollection();
                foreach($userGroupes as $userGroupe){
                    $users->add($userGroupe->getUser());
                }
                $form->get('users')->setData($users);
            }*/

            // Gestion des droits
            /*if($form->has('droits')){
                $groupeDroits = $event->getData()->getGroupeDroits();
                $droits = new ArrayCollection();
                foreach($groupeDroits as $groupeDroit){
                    $droits->add($groupeDroit->getDroit());
                }
                $form->get('droits')->setData($droits);
            }*/
        });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sofie\ExpBundle\Entity\Profile',
            'intention' => 'sofie_adminbundle_profil_type'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sofie_adminbundle_profil';
    }
}
