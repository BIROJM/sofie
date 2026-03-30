<?php

namespace Sofie\StatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SofieStatBundle:Default:index.html.twig', array('name' => $name));
    }
}
