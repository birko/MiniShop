<?php

namespace Site\DefaultBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Core\ShopBundle\Form\BaseAddressType;

class ContactClaimType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', new BaseAddressType(), array('required' => true))
            ->add('orderNumber', 'text', array(
                    'required' => true,
                    'label' => 'Order number',
                    'constraints' => array(
                        new Constraints\NotBlank(),
                    ),
                    'attr' => array(
                        'placeholder' => 'Číslo objednávky*',
                    ),
            ))
            ->add('productNumber', 'text', array(
                    'required' => false,
                    'label' => 'Product number',
                    'attr' => array(
                        'placeholder' => 'Číslo produktu',
                    ),
            ))
            ->add('accountNumber', 'text', array(
                    'required' => false,
                    'label' => 'Account number',
                    'attr' => array(
                        'placeholder' => 'Číslo účtu',
                    ),
            ))
            ->add('message', 'textarea', array(
                    'required' => true,
                    'constraints' => array(
                        new Constraints\NotBlank(),
                    ),
                    'attr' => array(
                        'placeholder' => 'Správa*'
                    )
                ))
            ->add('verification_code', 'text', array(
                'required' => true,
                'constraints' => array(
                        new Constraints\NotBlank(),
                ),
                'attr' => array(
                    'placeholder' => 'Verifikačný kód*'
                )
            ))
        ;
    }
    
    public function getName()
    {
        return 'contactclaim';
    }

}
