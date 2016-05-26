<?php
namespace Cupon\OfertaBundle\Listener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormError;

class OfertaTypeListener
{
	public function preSubmit(FormEvent $event)
	{
		$formulario=$event->getForm();
		
		if (false==$formulario->get('acepto')->getData()){
			$formulario->get('acepto')->addError(new FormError('Debes aceptar las condiciones legales'));
		}
	}
}