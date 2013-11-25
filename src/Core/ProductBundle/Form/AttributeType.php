<?php

namespace Core\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttributeType extends AttributeTranslationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!empty($options['cultures']))
        {
            $builder->add('translations', 'collection', array(
                'type' => new AttributeTranslationType(),
                'allow_add' => false,
                'allow_delete' => false,
                'prototype' => false, 
                'by_reference' => false,
                'options' => array(
                    'required' => false,
            )));
        }
        else
        {
            parent::buildForm($builder, $options);
        }
        $builder
            ->add('group', 'text',  array(
                'required' => false
            ))
        ;
    }
    
    public function getName()
    {
        return 'nws_productbundle_attributetype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'cultures' => array(),
        ));
    }
}
