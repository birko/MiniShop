<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints;
use Doctrine\ORM\EntityRepository;


class DeliveryAddressType extends BaseAddressType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('email', 'text', array(
                'required' => true,
                'constraints' => array(
                    new Constraints\NotBlank(),
                ),
                'attr' => array(
                    'placeholder' => 'Email',
                ))
            )
            ->add('phone', 'text', array(
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Phone',
                ))
            );
    }

}
