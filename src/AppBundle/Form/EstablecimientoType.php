<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class EstablecimientoType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('nombre','text', array('attr' => array('size' => '30px')))
            ->add('descripcion','text', array('attr' => array('size' => '30px')))
            ->add('direccion','text', array('attr' => array('size' => '30px')))
            ->add('telefono','text', array('attr' => array('size' => '30px')))
            ->add('sitioWeb','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('facebook','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('twitter','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('snapchat','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('youtube','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('instagram','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('correo','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('whatsapp','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('peso',NumberType::class, array('attr' => array('size' => '30px')))
            ->add('file','file',array('required' => false))
            
             
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Establecimiento'
        ));
    }

    /**
     * @return string
     */
    public function getName(){
        return 'establecimiento';
    }
}
