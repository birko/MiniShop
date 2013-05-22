<?php

namespace Core\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('current_password', 'password')
            ->add('new_password','repeated', array(
                'first_name' => 'password',
                'second_name' => 'confirm',
                'invalid_message' => 'The password and confirm fields must match.',
                'type' => 'password'
            ))
            ->setAttribute('show_legend', false);
    }

//    public function setDefaultOptions(array $options)
//    {
//        $collectionConstraint = new Collection(array(
//                    'pattern' => new MinLength(8),
//                    'capacity' => new Type('int'),
//                    'quantity' => new Type('int'),
//                ));
//        return array('validation_constraint' => $collectionConstraint);
//    }

    public function getName()
    {
        return 'user';
    }
}
