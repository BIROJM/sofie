<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 02/07/2015
 * Time: 14:24
 */

namespace Sofie\ExpBundle\Form\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\ExpBundle\Entity\Region;
use Sofie\UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddLocaliteSubscriber implements EventSubscriberInterface
{
    private $builder;
    private $em;
    private $site;
    private $region;

    public function __construct(FormBuilderInterface $builder, EntityManager $em, $site)
    {
        $this->em = $em;
        $this->builder = $builder;
        $this->site = $site;
        $this->region = $this->em->getRepository('SofieExpBundle:Region')->getOneByUserRegion();
        if($this->builder->has('region')){
            $this->builder->get('region')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
                $region = $event->getForm()->getData();
                if((!is_null($this->site) && intval($this->site)>0) && is_null($region)){
                    $region = $this->region;
                }
                $this->formModifier($event->getForm()->getParent(), $region);
            });
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData', FormEvents::POST_SET_DATA => 'postSetData'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $region = null;
        if(is_object($event->getData()) && method_exists($event->getData(), 'getRegion')){
            $region = $event->getData()->getRegion();
        }
        if(is_null($region)){
            $region = $this->region;
        }
        if(!$event->getForm()->has('localite')){
            $this->formModifier($event->getForm(), $region);
        }
    }

    public function postSetData(FormEvent $event)
    {
        if($this->builder->has('region')){
            $data = $event->getData();
            $form = $event->getForm();
            if(is_object($data) || (!is_null($data) && is_null($data->getId()))){
                if(method_exists($data, 'getLocalite')){
                    if(!is_null($data->getLocalite()) && !is_null($data->getLocalite()->getId())){
                        $form->get('region')->setData($data->getLocalite()->getRegion());
                    }
                }
            }
        }
    }

    private function formModifier(FormInterface $form, Region $region = null)
    {
        $formOptions = array(
            'required'=>true, 'trim'=>true, 'label'=>'Localité',
            'class'=>'SofieExpBundle:Localite',
            'choice_label' => 'nom',
            'empty_data'=>null,
            'placeholder'=>'',
            'query_builder' => function(EntityRepository $er) use ($region){
                return $er->getByRegionBuilder($region);
            }
        );
        $form->add('localite', 'entity', $formOptions);
    }
}