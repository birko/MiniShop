<?php

namespace Core\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProductType extends AbstractType
{
    protected $tags = array();
    
    public function __construct($tags = array())
    {
        $this->tags  = $tags;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('required' => true))
            ->add('vendor', 'entity',  array(
                'class' => 'CoreVendorBundle:Vendor',
                'property' => 'title' ,
                'query_builder' => function(EntityRepository $er) {
                return $er->createQueryBuilder('v')->orderBy('v.title', 'ASC');
                },
                'required'    => false,
                'empty_value' => 'Choose Vendor',
                'empty_data'  => null))
            ->add('shortDescription', 'textarea', 
                array(
                    'required' => false,
                    'label' => 'Short description',
            ))
            ->add('longDescription', 'textarea', array(
                'required' => false,
                'label' => 'Long description',
            ))
           ->add('enabled', 'checkbox', array('required' => false))
        ;
        if(!empty($this->tags))
        {
            $tags = array();
            foreach($this->tags as $tag)
            {
                $tags[$tag] = $tag;
            }
            
            $builder->add('tags', 'choice', array(
                'required' => false,
                'choices' => $tags,
                'multiple' => true,
                'expanded' => true,
            ));
        }
    }

    public function getName()
    {
        return 'core_productbundle_producttype';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Core\ProductBundle\Entity\Product',
        ));
    }
}
