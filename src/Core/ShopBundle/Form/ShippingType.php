<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Core\PriceBundle\Form\AbstractPriceType;

class ShippingType extends AbstractPriceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => true));
        parent::buildForm($builder, $options);
        $builder->add('description', 'textarea', array('required' => false))
            ->add('state','entity',  array(
                'class' => 'CoreShopBundle:State',
                'label' => 'State',
                'property' => 'name' ,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'required'    => true,
                'empty_value' => 'Choose state',
                'empty_data'  => null))
        ;
    }

    public function getName()
    {
        return 'core_shopbundle_shippingtype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ShopBundle\Entity\Shipping',
        ));
    }
}
