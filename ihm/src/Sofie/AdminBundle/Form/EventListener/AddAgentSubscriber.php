<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 02/07/2015
 * Time: 14:24
 */

namespace Sofie\AdminBundle\Form\EventListener;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sofie\ExpBundle\Entity\Region;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddAgentSubscriber implements EventSubscriberInterface
{
    private $builder;
    protected $em;
    protected $site;
    protected $region;
    public function __construct(FormBuilderInterface $builder, EntityManager $em, $site)
    {
        $this->builder = $builder;
        $this->em = $em;
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
        return array(FormEvents::PRE_SET_DATA => 'preSetData', FormEvents::POST_SET_DATA => 'postSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $region = $event->getData()->getRegion();
        if(is_null($region)){
            $region = $this->region;
        }
        if(!$event->getForm()->has('agent')){
            $this->formModifier($event->getForm(), $region);
        }
    }

    public function postSetData(FormEvent $event)
    {
        if($this->builder->has('region')){
            $data = $event->getData();
            $form = $event->getForm();
            if($data || is_null($data->getId())){
                if(!is_null($data->getAgent()) && !is_null($data->getAgent()->getId())){
                    $form->get('region')->setData($data->getAgent()->getRegion());
                }
            }
        }
    }

    private function formModifier(FormInterface $form, Region $region = null)
    {
        $formOptions = array(
            'required'=>false, 'trim'=>true, 'label'=>'Agent',
            'class'=>'SofieExpBundle:Agent',
            'placeholder'=>'',
            'empty_data'=>null,
            'query_builder' => function(EntityRepository $er) use ($region){
                return $er->getByRegionNotUserBuilder($region);
            }
        );

        $form->add('agent', 'entity', $formOptions);
    }
}