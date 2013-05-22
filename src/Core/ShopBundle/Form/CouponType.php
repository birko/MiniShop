<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array('required' => true))
            ->add('discountPerc', 'text', array(
                'required' => false,
                'label' => 'Discount percentage'
                ))
            ->add('price', 'text', array('required' => false))
            ->add('priceVAT', 'text', array(
                'required' => false,
                'label' => 'Price VAT'
                ))
            ->add('active', 'checkbox', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'core_shopbundle_coupontype';
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ShopBundle\Entity\Coupon',
        ));
    }
}
