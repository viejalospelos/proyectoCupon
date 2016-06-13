<?php
namespace Cupon\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CiudadType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('nombre')
			->add('slug')
			->add('guardar', 'submit')
			;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class'=>'Cupon\CiudadBundle\Entity\Ciudad'
		));
	}
	
	public function getName()
	{
		return 'cupon_backend_ciudad';
	}
}