<?php
namespace Cupon\OfertaBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
	/**
	 * Ejemplo de cómo añadir un nuevo formato a la aplicación
	 * Sirve para poder devolver respuestas en formato pdf
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		$event->getRequest()->setFormat('pdf', 'application/pdf');
	}
}