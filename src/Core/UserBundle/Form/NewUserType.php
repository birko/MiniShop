<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewUserType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('password', 'repeated', array(
                'required' => true,
                'type' => 'password',
                'invalid_message' => 'The password fields must match,', 
                'first_options' => array(
					'label' => 'Password', 
					'attr' => array(
						'placeholder' => 'Heslo *',
				)),
                'second_options' => array(
					'label' => 'Repeat Password',
					'attr' => array(
						'placeholder' => 'Opakovať Heslo *',
				))
            ))           
        ;
    }
}
