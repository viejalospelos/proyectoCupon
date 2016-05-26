<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Cupon\TiendaBundle\Form\Extranet\TiendaType;
use Cupon\OfertaBundle\Entity\Oferta;
use Cupon\OfertaBundle\Form\Extranet\OfertaType;

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
	
	public function portadaAction()
	{
		$em=$this->getDoctrine()->getManager();
		
		$tienda=$this->get('security.context')->getToken()->getUser();
		$ofertas=$em->getRepository('TiendaBundle:Tienda')->findOfertasRecientes($tienda->getId());
		
		return $this->render('TiendaBundle:Extranet:portada.html.twig', array(
				'ofertas'=>$ofertas
		));
	}
	
	public function ofertaVentasAction($id)
	{
		$em=$this->getDoctrine()->getManager();
		
		$ventas=$em->getRepository('OfertaBundle:Oferta')->findVentasByOferta($id);
		
		return $this->render('TiendaBundle:Extranet:ventas.html.twig', array(
				'oferta'=>$ventas[0]->getOferta(),
				'ventas'=>$ventas
		));
	}
	
	public function perfilAction(Request $peticion)
	{
		$tienda=$this->get('security.context')->getToken()->getUser();
		$formulario=$this->createForm(new TiendaType(), $tienda);
		$passwordOriginal=$formulario->getData()->getPassword();
		
		$formulario->handleRequest($peticion);
		
		if ($formulario->isValid()){
			if (null==$tienda->getPassword()){
				$tienda->setPassword($passwordOriginal);
			}else{
				$encoder=$this->get('security.encoder_factory')->getEncoder($tienda);
				$passwordCodificado=$encoder->encodePassword(
						$tienda->getPassword(),
						$tienda->getSalt()
						);
				$tienda->setPassword($passwordCodificado);
			}
			$em=$this->getDoctrine()->getManager();
			$em->persist($tienda);
			$em->flush();
			
			$this->get('session')->getFlashBag()->add('info', 'Los datos de tu perfil se han actualizado correctamente');
			return $this->redirect($this->generateUrl('extranet_portada'));
		}
		
		return $this->render('TiendaBundle:Extranet:perfil.html.twig', array(
				'tienda'=>$tienda,
				'formulario'=>$formulario->createView()
		));
	}
	
	public function ofertaNuevaAction(Request $peticion)
	{
		$oferta=new Oferta();
		$formulario=$this->createForm(new OfertaType(), $oferta);
		
		$formulario->handleRequest($peticion);
		
		if ($formulario->isValid()){
			$tienda=$this->get('security.context')->getToken()->getUser();
			
			$oferta->setCompras(0);
			$oferta->setRevisada(false);
			$oferta->setTienda($tienda);
			$oferta->setCiudad($tienda->getCiudad());
			
			$oferta->subirFoto($this->container->getParameter('cupon.directorio.imagenes'));
			
			$em=$this->getDoctrine()->getManager();
			$em->persist($oferta);
			$em->flush();
			
			return $this->redirect($this->generateUrl('extranet_portada'));
		}
		
		return $this->render('TiendaBundle:Extranet:formulario.html.twig', array(
				'formulario'=>$formulario->createView()
		));
	}
}
