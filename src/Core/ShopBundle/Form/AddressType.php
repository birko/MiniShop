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
                'required' => false,
                'attr' => array(
                    'placeholder' => 'DIČ',
                )
            ))
            ->add('OIN', 'text', array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'IČO',
                )
            ))
            ->add('VATIN', 'text', array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'IČ DPH',
                )
            ));
    }
}
