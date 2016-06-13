<?php

namespace Cupon\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfertaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('slug')
            ->add('descripcion')
            ->add('condiciones')
            ->add('rutaFoto')
            ->add('precio')
            ->add('descuento')
            ->add('fechaPublicacion', 'date')
            ->add('fechaExpiracion', 'date')
            ->add('compras')
            ->add('umbral')
            ->add('revisada')
            ->add('ciudad')
            ->add('tienda')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cupon\OfertaBundle\Entity\Oferta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cupon_ofertabundle_oferta';
    }
}
