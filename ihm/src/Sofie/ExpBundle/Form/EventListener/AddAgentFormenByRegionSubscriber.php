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
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddAgentFormenByRegionSubscriber implements EventSubscriberInterface
{
    private $builder;
    private $em;
    protected $site;
    protected $region;

    public function __construct(FormBuilderInterface $builder, EntityManager $em, $site)
    {
        $this->em = $em;
        $this->site = $site;
        $this->builder = $builder;
        $this->region = $this->em->getRepository('SofieExpBundle:Region')->find(intval($this->site));
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
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        if(!is_null($this->site) && !is_null($data) && method_exists($data, 'getRegion') && is_null($data->getRegion())){
            $data->setRegion($this->region);
        }
        $this->formModifier($event->getForm(), $data->getRegion());

    }

    private function formModifier(FormInterface $form, Region $region = null)
    {
        $formOptions = array(
            'required'=>true, 'trim'=>true, 'label'=>'Agent Formen',
            'class'=>'SofieExpBundle:Agent',
            'placeholder'=>'',
            'empty_data'=>null,
            'query_builder' => function(EntityRepository $er) use ($region){
                return $er->getAgentsFormenByRegionBuilder($region);
            }
        );

        $form->add('agentForma', 'entity', $formOptions);
    }
}