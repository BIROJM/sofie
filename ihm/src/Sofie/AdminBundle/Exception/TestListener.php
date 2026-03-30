<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 14/09/2015
 * Time: 11:08
 */

namespace Sofie\AdminBundle\Exception;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class TestListener
{
    protected $test;
    protected $msg;

    public function __construct(Test $test, $msg)
    {
        $this->test = $test;
        $this->msg = $msg;
    }

    public function processMsg(FilterResponseEvent $event)
    {
        $respnse = $this->test->displayMsg($event->getResponse(), $this->msg);
        $event->setResponse($respnse);
    }
}