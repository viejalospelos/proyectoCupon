<?php

namespace Cupon\OfertaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    public function portadaAction($ciudad)
    {
        $em=$this->getDoctrine()->getManager();
        $oferta=$em->getRepository('OfertaBundle:Oferta')->findOfertaDelDia($ciudad);
        
        /*if (!$oferta){
        	throw $this->createNotFoundException('Mierda, no se encuentra oferta del día');
        }*/
        
        return $this->render('OfertaBundle:Default:portada.html.twig',
        array('oferta'=>$oferta)
        );		
    }
}
