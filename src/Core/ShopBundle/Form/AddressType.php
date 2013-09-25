<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


class AddressType extends DeliveryAddressType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('TIN', 'text', array(
                'required' => isset($options['requiredFields']['TIN']) ? $options['requiredFields']['TIN'] : false,
                'label' => 'TIN',
                'attr' => array(
                    'placeholder' => 'TIN',
                )
            ))
            ->add('OIN', 'text', array(
                'required' => false,
                'label' => isset($options['requiredFields']['OIN']) ? $options['requiredFields']['OIN'] : 'OIN',
                'attr' => array(
                    'placeholder' => 'OIN',
                )
            ))
            ->add('VATIN', 'text', array(
                'required' => false,
                'label' => isset($options['requiredFields']['VATIN']) ? $options['requiredFields']['VATIN'] : 'VATIN',
                'attr' => array(
                    'placeholder' => 'VATIN',
                )
            ));
    }
}
