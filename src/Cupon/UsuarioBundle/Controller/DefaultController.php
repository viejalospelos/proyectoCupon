<?php

namespace Cupon\UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\UsuarioBundle\Entity\Usuario;
use Cupon\UsuarioBundle\Form\Frontend\UsuarioType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


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
    
    /**
     * Muestra todas las compras del usuario logueado
     */
    public function comprasAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$usuario = $this->get('security.context')->getToken()->getUser();
    
    	$cercanas = $em->getRepository('CiudadBundle:Ciudad')->findCercanas(
    			$usuario->getCiudad()->getId()
    			);
    
    	$compras = $em->getRepository('UsuarioBundle:Usuario')->findTodasLasCompras($usuario->getId());
    
    	return $this->render('UsuarioBundle:Default:compras.html.twig', array(
    			'compras'  => $compras,
    			'cercanas' => $cercanas
    	));
    }
//controlador para generar y procesar formularios
//el mismo controlador genera y procesa
//el procesado se hace mediante handleRequest()y isValid()
    public function registroAction(Request $peticion)
    {
    	$usuario = new Usuario();
    	$usuario->setPermiteMail(true);
    	$usuario->setFechaNacimiento(new \DateTime('today - 18 years'));
    	
    	$formulario = $this->createForm(new UsuarioType(),$usuario);
    	
    	$formulario->handleRequest($peticion);
    	if ($formulario->isValid()){
    		$encoder=$this->get('security.encoder_factory')->getEncoder($usuario);
    		$usuario->setSalt(md5(time()));
    		$passwordCodificado=$encoder->encodePassword(
    				$usuario->getPassword(),
    				$usuario->getSalt()
    				);
    		$usuario->setPassword($passwordCodificado);
    		
    		$em=$this->getDoctrine()->getManager();
    		$em->persist($usuario);
    		$em->flush();
    		
    		//mensaje flash
    		$this->get('session')->getFlashBag()->add('info','Enhorabuena, te has registrado correctamente en Cupon');
    		
    		//logeamos automáticamente al usuario
    		$token= new UsernamePasswordToken(
    				$usuario,
    				$usuario->getPassword(),
    				'frontend',
    				$usuario->getRoles()
    				);
    		$this->container->get('security.context')->setToken($token);
    		
    		return $this->redirect($this->generateUrl('portada', array('ciudad'=>$usuario->getCiudad()->getSlug())));
    	}

    	return $this->render(
    			'UsuarioBundle:Default:registro.html.twig',
    			array('formulario' => $formulario->createView())
    			);
    }
    
    public function perfilAction(Request $peticion)
    {
    	// Obtener los datos del usuario logueado y utilizarlos para
    	// rellenar un formulario de registro.
    	//
    	// Si la petición es GET, mostrar el formulario
    	// Si la petición es POST, actualizar la información del usuario con
    	// los nuevos datos obtenidos del formulario
    	
    	$usuario=$this->get('security.context')->getToken()->getUser();
    	$formulario=$this->createForm(new UsuarioType(), $usuario);
    	//Hay que eliminar el botón del formulario que pone Regisrarme y añadir otro que ponga Guardar cambios
    	$formulario
    		->remove('registrarme')
    		->add('guardar','submit',array('label'=>'Guardar cambios'));
    	
    	//guardamos una copia del password antiguo
    	$passwordOriginal=$formulario->getData()->getPassword();
    	$formulario->handleRequest($peticion);
    	
    	if ($formulario->isValid()){
    		//si no quiere modificar el password, estará en blanco. Para que no introduzca el valor null en la DB, le pasamos la copia del password antiguo.
    		//en caso contrario, codificamos el nuevo password
    		if(null==$usuario->getPassword()){
    			$usuario->setPassword($passwordOriginal);
    		}else{
    			$encoder=$this->get('security.encoder_factory')->getEncoder($usuario);
    			$passwordCodificado=$encoder->encodePassword($usuario->getPassword(), $usuario->getSalt());
    			$usuario->setPassword($passwordCodificado);
    		}
    		//actualizar perfil de usuario
    		$em=$this->getDoctrine()->getManager();
    		$em->persist($usuario);
    		$em->flush();
    		
    		$this->get('session')->getFlashBag()->add('info', 'Los datos de tu perfil se han actualizado correctamente');
    		return $this->redirect($this->generateUrl('usuario_perfil'));
    	}
    	
    	return $this->render('UsuarioBundle:Default:perfil.html.twig', array(
    			'usuario'=>$usuario,
    			'formulario'=>$formulario->createView()
    	));
    }
    
    
}
