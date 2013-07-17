<?php

namespace Site\MarketingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;

class ContactMultiType extends ContactType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
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
                ->add('copy', 'checkbox', array(
                    'required' => false,
                    'label' => 'Send copy to your email',
                ))
                ->add('type', 'choice', array(
                    'required' => true,
                    'constraints' => array(
                        new Constraints\NotBlank(),
                    ),
                    'choices'=> array(
                        "Produkt" => 'Produkt',
                        "Objednávka" => 'Objednávka',
                        "Faktúra" => 'Faktúra',
                        "Iné" => 'Iné',
                    ),
                ))

                
        ;
    }

    public function getName()
    {
        return 'contact';
    }

}
