<?php
namespace Cupon\UsuarioBundle\Form\Frontend;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType 
{
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder
			->add('nombre')
			->add('apellidos')
			->add('email','email')
			->add('password','repeated',array(
					'type'=>'password',
					'invalid_message'=>'Las dos contraseñas tienen que coincidir',
					'first_options'=>array('label'=>'Contraseña'),
					'second_options'=>array('label'=>'Repite Contraseña')
			))
			->add('direccion')
			->add('permite_mail','checkbox',array('required'=>false))
			->add('fecha_nacimiento','birthday')
			->add('dni')
			->add('numero_tarjeta')
			->add('ciudad')
			->add('registrarme','submit');
	}
	//con setDefaultOptions se indica el namespace de la entidad vinculada
	//error_mapping es una opción para que cuando haya un error de validación, el mensaje aparezca junto al campo. Se usa si utilizamos validaciones ad-hoc
	public function setDefaultOptions(OptionsResolverInterface $resolver){
		$resolver->setDefaults(array(
				'data_class'=>'Cupon\UsuarioBundle\Entity\Usuario',
				'error_mapping'=>array(
						'mayorDeEdad'=>'fecha_nacimiento'
				)
		));
	}
	//con getName se define un nombre único para el formulario
	public function getName(){
		return 'cupon_usuariobundle_usuariotype';
	}
}