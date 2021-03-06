<?php

namespace Cupon\TiendaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function portadaAction($ciudad, $tienda)
    {
        $em=$this->getDoctrine()->getManager();
        
        $ciudad=$em->getRepository('CiudadBundle:Ciudad')->findOneBySlug($ciudad);
        
        $tienda=$em->getRepository('TiendaBundle:Tienda')->findOneBy(array(
        		'slug'=> $tienda,
        		'ciudad'=>$ciudad->getId()
        ));        
        if (!$tienda){
        	throw $this->createNotFoundException('No existe esa tienda');
        }
        
        $ofertas=$em->getRepository('TiendaBundle:Tienda')->findUltimasOfertasPublicadas($tienda->getId());
        
        $cercanas=$em->getRepository('TiendaBundle:Tienda')->findCercanas(
        		$tienda->getSlug(),
        		$tienda->getCiudad()->getSlug()
        		);
        
        //compatibilizando con rss
        $formato=$this->get('request')->getRequestFormat();
        
        return $this->render('TiendaBundle:Default:portada.'.$formato.'.twig', array(
        		'tienda'=>$tienda,
        		'ofertas'=>$ofertas,
        		'cercanas'=>$cercanas
        ));
    }
}
