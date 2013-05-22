<?php
namespace Site\ShopBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Description of CartPaymentShippingType
 *
 * @author Birko
 */
class CartPaymentShippingType extends AbstractType
{
    protected $state = null;
    
    public function __construct($state = null)
    {
        $this->state = $state;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $state = $this->state;
        $builder->add("payment", 'entity', array(
                'class' => 'CoreShopBundle:Payment',
                'expanded' => true,
                'multiple' => false,
                'query_builder' => function(EntityRepository $er) use($state) {
                    $qb = $er->getPaymentQueryBuilder();
                    return $qb;
                },
            ))
            ->add("shipping", 'entity', array(
            'class' => 'CoreShopBundle:Shipping',
            'expanded' => true,
            'multiple' => false,
            'query_builder' => function(EntityRepository $er) use($state) {
                $qb =  $er->getShippingQueryBuilder($state);
                return $qb;
            },
        ));
    }
    
    public function getName() 
    {
        return "nws_shop_paymentshipping";
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\ShopBundle\Entity\Cart',
        ));
    }
}

?>
