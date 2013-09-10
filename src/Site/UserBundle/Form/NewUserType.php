<?php

namespace Site\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Core\UserBundle\Form\NewUserType as BaseUserType;
use Core\ShopBundle\Form\AddressType;

class NewUserType extends BaseUserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
        ->add('addresses', 'collection', array(
                'type' => new AddressType(),
                'allow_add' => true,
                'allow_delete' => true,
                'widget_add_btn' => array('label' => 'Add'),
                'show_legend' => false,
                'prototype' => true, 
                'by_reference' => false,
                'options' => array(
                    'required' => false,
                    'widget_remove_btn' => array('label' => 'Remove'),
                    'label_render' => false,
                )))
        ->remove('enabled')
        ->remove('priceGroup')
        ;
    }
}
