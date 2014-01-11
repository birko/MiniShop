<?php

namespace Site\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\ShopBundle\Entity\CartItem;
use Site\ShopBundle\Entity\Cart;
use Site\ShopBundle\Form\CouponType;
use Site\ShopBundle\Entity\CouponItem;

class GrouponController extends ShopController
{  
    
    public function addAction($product)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') 
        {
            $entity = (object)array('code'=>'');
            $form = $this->createForm(new CouponType(), $entity);
            $form->bind($request);
            $form->isValid();
            $cart = $this->getCart();
            $entities = $em->getRepository('CoreProductBundle:Groupon')->getProductGrouponsQueryBuilder($product, true)->getQuery()->getResult();
            if($entities)
            {
                $coupon = current($entities);
                $product = $coupon->getProduct();
                $data= array('code' => $entity->code);
                $data['name'] = $product->getTitle(). "({$entity->code})";
                $data['productId'] = $product->getId();
                $data['amount'] = $coupon->getAmount();
                if($coupon->getPrice()) // price coupon
                {
                    $data['price'] = $coupon->getPrice() / $count;
                    $data['priceVAT'] = $coupon->getPriceVAT() / $count;
                }
                else  //percentage coupon
                {
                    $price = $product->getPrices()->first();
                    if($price)
                    {
                        $data['price'] = $price->getPrice() *  (1 - $coupon->getDiscount());
                        $data['priceVAT'] = $price->getPriceVAT() * (1 - $coupon->getDiscount());
                    }
                    else
                    {
                        $data['price'] = 0;
                        $data['priceVAT'] = 0;
                    }
                }
                $this->addData($cart, $data);
                if($coupon->getPayment())
                {
                    $cart->setPayment($coupon->getPayment());
                    $cart->setSkipPayment(true);
                }
                if($coupon->getShipping())
                {
                    $cart->setShipping($coupon->getShipping());
                    $cart->setSkipShipping(true);
                }
                $this->setCart($cart);
            }
        }
        return $this->redirect($this->generateUrl('cart'));
    }
    
    public function formAction($product)
    {
        $form = $this->createForm(new CouponType());
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('CoreProductBundle:Groupon')->getProductGrouponsQueryBuilder($product, true)->getQuery()->getResult();
        return $this->render('SiteShopBundle:Groupon:form.html.twig', array(
            'form'   => $form->createView(),
            'entities' => $entities,
            'product' => $product,
        ));
    }
    
    protected function addData(Cart $cart, $data = array())
    {
        if(!empty($data))
        {
            $index = $cart->findItem($data);
            $new = false;
            if($index === false)
            {
                $item = new CouponItem();
                $index = $cart->getItemsCount();
                $new = true;
            }
            else
            {
                $item = $cart->getItem($index);
                if($item instanceof CouponItem)
                {
                    $new = false;
                }
                else
                {
                    $item = new CouponItem();
                    $index = $cart->getItemsCount();
                    $new = true;
                }
            }
            if($item != null && $new)
            {
                $item->addAmount($data['amount']);
                $item->setProductId($data['productId']);
                $item->setName($data['name']);
                $item->setPrice($data['price']);
                $item->setPriceVAT($data['priceVAT']);
                $item->setCode($data['code']);
                $cart->addItem($item, $index);
            }
        }
    }
}
