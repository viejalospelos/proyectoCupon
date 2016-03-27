<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;


class DefaultController extends Controller
{
    public function loginAction(Request $peticion)
    {
		$sesion=$peticion->getSession();
		// obtener, si existe, el error producido en el último intento de login
		//Busca errores de autenticacion para informar al usuario si ha escrito mal el password o el nombre
		//Primero  trata de obtener la información de la propiedad attributes y si no lo encuentra va a buscarlo en la sesión activa
		// Todo esto se saca de la clase securitycontext
		if ($peticion->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
			$error=$peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		}else{
			$error=$sesion->get(SecurityContext::AUTHENTICATION_ERROR);
			$sesion->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		
		return $this->render('UsuarioBundle:Default:login.html.twig', array(
				'last_username'=>$sesion->get(SecurityContext::LAST_USERNAME),
				'error'		   =>$error	
		));
    }
    public function cajaLoginAction(Request $peticion)
    {
    	$sesion=$peticion->getSession();

    	if ($peticion->attributes->has(SecurityContext::AUTHENTICATION_ERROR)){
    		$error=$peticion->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    	}else{
    		$error=$sesion->get(SecurityContext::AUTHENTICATION_ERROR);
    		$sesion->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}
    
    	return $this->render('UsuarioBundle:Default:cajaLogin.html.twig', array(
    			'last_username'=>$sesion->get(SecurityContext::LAST_USERNAME),
    			'error'		   =>$error
    	));
    }
}
