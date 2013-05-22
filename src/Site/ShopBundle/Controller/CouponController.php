<?php

namespace Site\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\ShopBundle\Entity\CartItem;
use Site\ShopBundle\Entity\Cart;
use Site\ShopBundle\Form\CouponType;
use Site\ShopBundle\Entity\CouponItem;
/**
 * Address controller.
 *
 */
class CouponController extends ShopController
{  
    
    public function addAction()
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
            $coupon  = $em->getRepository("CoreShopBundle:Coupon")->findOneByCode($entity->code);
            
            if($coupon) //in system
            {
                if($coupon->isActive())
                {
                    $count = $coupon->getProducts()->count();
                    if( $count > 0) //product cupon
                    {
                        foreach($coupon->getProducts() as $product)
                        {
                            $data= array('code' => $coupon->getCode());
                            $data['name'] = $product->getTitle(). "({$coupon->getCode()})";
                            $data['productId'] = $product->getId();
                            $data['amount'] = 1;
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
                        }
                    }
                    else //discount coupon
                    {
                        $data= array('code' => $coupon->getCode());
                        $data['name'] = 'Coupon: '. "({$coupon->getCode()})";
                        $data['amount'] = 1;
                        $data['productId'] = null;
                        if($coupon->getPrice()) // price coupon
                        {
                            $data['price'] = $coupon->getPrice() * (-1);
                            $data['priceVAT'] = $coupon->getPriceVAT() * (-1);
                        }
                        else  //percentage coupon
                        {
                            $data['price'] = $coupon->getDiscount() * $cart->getPrice() * (-1);
                            $data['priceVAT'] = $coupon->getDiscount() * $cart->getPriceVAT() * (-1);
                        }
                        $this->addData($cart, $data);
                    }
                }
            }
            else 
            {
                $data= array('code' => $entity->code);
                $data['name'] = 'Coupon'. "({$entity->code})";
                $data['amount'] = 1;
                $data['price'] = 0;
                $data['priceVAT'] = 0; 
                $data['productId'] = null;
                $this->addData($cart, $data);
            }
            $this->setCart($cart);
        }
        return $this->redirect($this->generateUrl('cart'));
    }
    
    public function formAction()
    {
        $form = $this->createForm(new CouponType());
        return $this->render('SiteShopBundle:Coupon:form.html.twig', array(
            'form'   => $form->createView(),
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
