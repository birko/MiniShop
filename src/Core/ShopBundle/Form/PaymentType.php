<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => true))
            ->add('price', 'text', array('required' => true))
            ->add('priceVAT', 'text', array(
                'required' => true,
                'label' => 'Price VAT',
            ))
            ->add('description', 'textarea', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'core_shopbundle_paymenttype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ShopBundle\Entity\Payment',
        ));
    }
}
