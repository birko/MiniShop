<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Core\PriceBundle\Form\AbstractPriceType;

class ShippingType extends AbstractPriceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(!empty($options['cultures']))
        {
            $builder->add('translations', 'collection', array(
                'type' => new ShippingTranslationType(),
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
        parent::parentBuildForm($builder, $options);
        $builder->add('state','entity',  array(
                'class' => 'CoreShopBundle:State',
                'label' => 'State',
                'property' => 'name' ,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'required'    => true,
                'empty_value' => 'Choose state',
                'empty_data'  => null))
        ;
    }

    public function getName()
    {
        return 'core_shopbundle_shippingtype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'cultures' => array(),
        ));
    }
}
