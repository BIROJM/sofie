<?php

namespace Sofie\UserBundle\PasswordEncoder;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sofie\UserBundle\Entity\User;

class SofiePasswordEncoder
{
	// Set User Password
	public function encodeUserPassword(User $user, Controller $controller)
	{
        $encoder = $controller->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $user->getPlainPassword());
		$user->setPassword($password);
		return $user;
	}
}
