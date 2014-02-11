<?php

namespace Core\ProductBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProductOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attributeName = $options['attributeName'];
        $builder->add('name', 'entity', array(
            'class' => 'CoreAttributeBundle:AttributeName',
            'expanded' => false,
            'required' => true,
            'multiple' => false,
            'query_builder' => function(EntityRepository $er) {
                $qb = $er->getNamesQueryBuilder();
                return $qb;
            },
        ));
        $builder->add('value', 'entity', array(
            'class' => 'CoreAttributeBundle:AttributeValue',
            'expanded' => false,
            'required' => true,
            'multiple' => false,
            'group_by' => 'attributeName',
            'query_builder' => function(EntityRepository $er) use ($attributeName) {
                $qb = $er->getValuesByNameQueryBuilder($attributeName);
                return $qb;
            },
        ));
        $builder
            ->add('amount', 'number', array('required' => false))
        ;
    }

    public function getName()
    {
        return 'core_productbundle_productoptiontype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ProductBundle\Entity\ProductOption',
            'attributeName'=> null,
        ));
    }
}
