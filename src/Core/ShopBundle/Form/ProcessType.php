<?php

namespace Core\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ProcessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
                'required' => true,
                'choices' => array( //FIXME: do konfigu
                    'labels' => "Štítky",
                    'orderstatus' => 'Stav Objednávky',
                    'shippingstatus' => 'Stav Dodania',
                    'export' => 'Exporty',
                )
            ))
            ->add('orderStatus','entity',  array(
                'class' => 'CoreShopBundle:OrderStatus',
                'label' => 'Order status',
                'property' => 'name' ,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'required'    => false,
                'empty_value' => 'Choose status',
                'empty_data'  => null))
            ->add('shippingStatus','entity',  array(
                'class' => 'CoreShopBundle:ShippingStatus',
                'label' => 'Shipping status',
                'property' => 'name' ,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('s')->orderBy('s.name', 'ASC');
                },
                'required'    => false,
                'empty_value' => 'Choose status',
                'empty_data'  => null))
            ->add('processOrders', 'collection', array(
                'type' => new ProcessOrderType(),  
                'label' => "Order",
                'required' => false,
                'allow_add' => true
            ));
        $exports = array();
        if(!empty($options['export']))
        {
            
            foreach($options['export'] as $key => $export)
            {
                $exports[$key] = $export['name'];
            }
        }
        $builder->add('export', 'choice', array(
                'required' => false,
                'choices' => $exports,
        ));
    }

    public function getName()
    {
        return 'core_shopbundle_processtype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ShopBundle\Entity\Process',
            'export' => array(),
        ));
    }
}
