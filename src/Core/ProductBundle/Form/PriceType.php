<?php

namespace Core\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'choices' => array(
                    'normal' => "Normal",
                    'special' => "Special",
                ),
                'required'    => true,
                'empty_value' => 'Choose price type',
                'empty_data'  => null))
            ->add('price', 'text', array('required' => true))
            ->add('priceVAT', 'text', array(
                'required' => true,
                'label' => 'Price VAT'
            ))
            ->add('priceGroup','entity',  array(
                'class' => 'CoreUserBundle:PriceGroup',
                'label' => 'Price group',
                'property' => 'name' ,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('pg')->orderBy('pg.name', 'ASC');
                },
                'required'    => true,
                'empty_value' => 'Choose Price Group',
                'empty_data'  => null))
            ->add('priceAmount', 'number', array(
                'required' => false,
                'label' => 'Price amount',
            ))
            ->add('default', 'checkbox', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'core_productbundle_pricetype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ProductBundle\Entity\Price',
        ));
    }
}
