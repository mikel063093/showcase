<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UsuarioType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('nombres','text', array('attr' => array('size' => '30px')))
            ->add('apellidos','text', array('attr' => array('size' => '30px')))
            ->add('correo','text', array('attr' => array('size' => '30px')))
            ->add('telefono','text', array('attr' => array('size' => '30px'),'required' => false))
            ->add('password','repeated', array(
                'type' => 'password',
                'invalid_message' => 'El campo confirmación no coincide con el de la contraseña.',
                'required' => false
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Usuario'
        ));
    }

    /**
     * @return string
     */
    public function getName(){
        return 'usuario';
    }
}
