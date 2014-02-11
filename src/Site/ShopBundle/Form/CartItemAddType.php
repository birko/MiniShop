<?php
namespace Site\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of CartItemType
 *
 * @author Birko
 */
class CartItemAddType extends CartItemType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('name', 'hidden');
        $builder->add('description', 'hidden');
        $builder->add('price', 'hidden');
        $builder->add('priceVAT', 'hidden');
        $builder->add('productId', 'hidden');
        $builder->add('options', new OptionCartItemAddType(), array(
                'required' => true,
                'options' => $options['options'],
                'product' => $options['product'],
            ));
    }
    
    public function getName() 
    {
        return "core_shop_cartitemaddtype";
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'options' => array(),
            'product' => null,
        ));
    }
}

?>