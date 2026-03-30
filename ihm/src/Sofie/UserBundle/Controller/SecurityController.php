<?php

namespace Sofie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
	public function sofieLoginAction(Request $request)
	{
        $helper = $this->get('security.authentication_utils');

        return $this->render('SofieUserBundle:Security:login.html.twig', array(
            'last_username' => $helper->getLastUsername(),
            'error'         => $helper->getLastAuthenticationError(),
        ));
	}
}
