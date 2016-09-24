<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class ContactoType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('nombreCompleto','text', array('attr' => array('size' => '30px'),'required' => false))
            ->add('correo','text', array('attr' => array('size' => '30px'),'required' => false))
            ->add('asunto','text', array('attr' => array('size' => '30px'),'required' => false))
            ->add('comentario',TextareaType::class, array('attr' => array('size' => '30px'),'required' => false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contactenos'
        ));
    }

    /**
     * @return string
     */
    public function getName(){
        return 'contacto';
    }
}
