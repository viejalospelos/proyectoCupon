<?php
namespace Cupon\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Cupon\CiudadBundle\Entity\Ciudad;
use Cupon\BackendBundle\Form\CiudadType;

class CiudadController extends Controller
{
	public function indexAction()
	{
		$em=$this->getDoctrine()->getManager();
		$ciudades=$em->getRepository('CiudadBundle:Ciudad')->findAll();
		
		return $this->render('BackendBundle:Ciudad:index.html.twig', array(
				'ciudades'=>$ciudades
		));
	}
	
	public function verAction($id)
	{
		$em=$this->getDoctrine()->getManager();
		$ciudad=$em->getRepository('CiudadBundle:Ciudad')->find($id);
		
		if (!$ciudad){
			throw $this->createNotFoundException('No existe esa ciudad');
		}
		
		return $this->render('BackendBundle:Ciudad:ver.html.twig', array(
				'ciudad'=>$ciudad
		));
	}
	
	public function crearAction(Request $request)
	{
		$ciudad= new Ciudad();
		$formulario=$this->createForm(new CiudadType(), $ciudad);
		
		$formulario->handleRequest($request);
		
		if ($formulario->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->persist($ciudad);
			$em->flush();
			
			return $this->render('BackendBundle:Ciudad:crear.html.twig', array(
					'formulario'=>$formulario->createView()
			));
		}
	}
	public function actualizarAction(Request $request, $id)
	{
		$em=$this->getDoctrine()->getManager();
		$ciudad=$em->getRepository('CiudadBundle:Ciudad')->find($id);
		
		if (!$ciudad){
			throw $this->createNotFoundException('No existe esa ciudad');
		}
		
		$formulario=$this->createForm(new CiudadType(), $ciudad);
		
		$formulario->handleRequest($request);
		
		if ($formulario->isValid()){
			$em=$this->getDoctrine()->getManager();
			$em->persist($ciudad);
			$em->flush();
			
			return $this->redirect($this->generateUrl('backend_ciudad'));
		}
		
		return $this->render('BackendBundle:Ciudad:actualizar.html.twig', array(
				'formulario'=>$formulario->createView(),
				'ciudad'=>$ciudad
		));
				
	}
	
	public function borrarAction($id)
	{
		$em=$this->getDoctrine()->getManager();
		$ciudad=$em->getRepository('CiudadBundle:Ciudad')->find($id);
		
		if (!$ciudad){
			throw $this->createNotFoundException('no existe esa ciudad');
		}
		
		$em->remove($ciudad);
		$em->flush();
		
		return $this->redirect($this->generateUrl('backend_ciudad'));
	}
	
}