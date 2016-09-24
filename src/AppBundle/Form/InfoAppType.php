<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class InfoAppType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('precioDomicilio',NumberType::class, array('attr' => array('size' => '30px')))
            ->add('nosotros',TextareaType::class, array('attr' => array('size' => '30px')))
            ->add('file','file',array('required' => false))
            
             
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\InformacionApp'
        ));
    }

    /**
     * @return string
     */
    public function getName(){
        return 'info';
    }
}
