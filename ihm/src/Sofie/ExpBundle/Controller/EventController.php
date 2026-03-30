<?php

namespace Sofie\ExpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    public function indexAction()
    {
        return $this->render('SofieExpBundle:Event:index.html.twig', array(
                // ...
            ));    }

}
