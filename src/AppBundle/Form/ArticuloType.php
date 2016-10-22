<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
class ArticuloType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('nombre','text', array('attr' => array('size' => '30px')))
            ->add('descripcion',CKEditorType::class, array(
                'config_name' => 'my_config'))
            ->add('precio',NumberType::class, array('attr' => array('size' => '30px')))
            ->add('unidadMedida','text', array('attr' => array('size' => '30px')))
            ->add('valorMedida','text', array('attr' => array('size' => '30px'), 'required' => false))
            ->add('cantidad',NumberType::class, array('attr' => array('size' => '30px'), 'required' => false))

            
             
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Articulo'
        ));
    }

    /**
     * @return string
     */
    public function getName(){
        return 'articulo';
    }
}
