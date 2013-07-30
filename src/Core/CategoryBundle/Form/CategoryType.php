<?php

namespace Core\CategoryBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => true))
            ->add('enabled', 'checkbox', array('required' => false))
            ->add('home', 'checkbox', array('required' => false))
            ->add('external', 'checkbox', array('required' => false))
            ->add('slug', 'text', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'core_categorybundle_categorytype';
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\CategoryBundle\Entity\Category',
        ));
    }
}
