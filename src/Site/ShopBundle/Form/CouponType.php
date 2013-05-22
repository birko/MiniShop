<?php

namespace Site\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CouponType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'text', array(
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Zadajte číslo kupónu'
                )
            ))
        ;
    }

    public function getName()
    {
        return 'site_shopbundle_coupontype';
    }
}
