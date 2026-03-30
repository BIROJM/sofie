<?php

namespace Sofie\ExpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExpController extends Controller
{
    public function indexAction()
    {
        return $this->render('SofieExpBundle:Exp:index.html.twig', array());
    }
}
