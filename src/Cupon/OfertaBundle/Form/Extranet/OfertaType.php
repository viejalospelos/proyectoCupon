<?php
namespace Cupon\OfertaBundle\Form\Extranet;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Cupon\OfertaBundle\Listener\OfertaTypeListener;


class OfertaType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) 
	{
		$builder
			->add('nombre')
			->add('descripcion')
			->add('condiciones')
			->add('foto', 'file', array('required'=>false))
			->add('descuento', 'money')
			->add('precio', 'money')
			->add('umbral')
			->add('guardar', 'submit', array(
					'label'=>'Guardar cambios',
					'attr'=>array('class'=>'boton')
			))
		;
//El campo adicional acepto sólo se debe incluir cuando se añade una oferta, no cuando
//se modifica. Se comprueba el valor de la propiedad id de la entidad: si vale null, la
//entidad todavía no se ha guardado en la base de datos y por tanto se está creando		
		if (null==$options['data']->getId()){
			$builder->add('acepto', 'checkbox', array(
					'mapped'=> false,
					'required'=> false
			));
			
			$listener= new OfertaTypeListener();
			$builder->addEventListener(FormEvents::PRE_SUBMIT, array($listener, 'preSubmit'));
		}
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class'=>'Cupon\OfertaBundle\Entity\Oferta'
		));
	}
	
	public function getName()
	{
		return 'oferta_tienda';
	}
}