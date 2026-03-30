<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 02/07/2015
 * Time: 14:24
 */

namespace Sofie\ExpBundle\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class AddNumeroAppelSubscriber implements EventSubscriberInterface
{

    static public function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        if(!is_null($data) && method_exists($data, 'getNumeroAppel')){
            $numeroAppel = $data->getNumeroAppel();
            if(!is_null($numeroAppel) && method_exists($numeroAppel, "getNumero") && !is_null($numeroAppel->getNumero())){
                $this->formModifier($event->getForm());
            }
        }
    }

    private function formModifier(FormInterface $form)
    {
        $form->add('numeroAppel', 'sofie_expbundle_numeroappel');
    }
}