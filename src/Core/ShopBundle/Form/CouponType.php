<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Core\PriceBundle\Form\AbstractPriceType;


class CouponType extends AbstractPriceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array('required' => true))
            ->add('discount', 'percent', array(
                'required' => false,
                'label' => 'Discount percentage'
                ));
        parent::buildForm($builder, $options);
        if($builder->has("price"))
        {
            $builder->get("price")->setRequired(false);
        }
        if($builder->has("priceVAT"))
        {
            $builder->get("priceVAT")->setRequired(false);
        }
        $builder->add('active', 'checkbox', array('required' => false))
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
