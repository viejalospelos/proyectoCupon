<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class ExtranetController extends Controller
{
	public function loginAction(Request $peticion)
	{
		$sesion=$peticion->getSession();
		
		$error=$peticion->attributes->get(
				SecurityContext::AUTHENTICATION_ERROR,
				$sesion->get(SecurityContext::AUTHENTICATION_ERROR)
				);
		
		return $this->render('TiendaBundle:Extranet:login.html.twig', array(
				'error'=>$error
		));
	}
}
