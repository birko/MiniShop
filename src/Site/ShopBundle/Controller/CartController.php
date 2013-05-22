<?php

namespace Site\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\ShopBundle\Entity\CartItem;
use Site\ShopBundle\Entity\Cart;
use Site\ShopBundle\Form\CartBaseType;
use Site\ShopBundle\Form\CartUserType;
/**
 * Address controller.
 *
 */
class CartController extends ShopController
{
    public function indexAction()
    {
        $cart = $this->getCart();
        $this->testCart();
        $user = $this->getShopUser();
        //TODO: Prerobit na volanie komponentov
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $form = $this->createForm(new CartBaseType(true));
        }
        else
        {
            if($user === null)
            {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            if($user->getAddresses()->count() > 0)
            {
                $cart->setPaymentAddress($user->getAddresses()->first());
                $cart->setShippingAddress($user->getAddresses()->first());
            }
            else
            {
                throw $this->createNotFoundException('Unable to find User Address entity.');
            }
            $form = $this->createForm(new CartUserType($user->getId(), true), $cart);
        }
        return $this->render('SiteShopBundle:Cart:index.html.twig', array(
            'form'   => $form->createView(),
            'cart' => $cart,
            'user' => $user,
        ));
    }
    
    public function checkAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $user = $this->getShopUser();
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $form = $this->createForm(new CartBaseType(true), $cart);
        }
        else
        {
            if($user === null)
            {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            if($user->getAddresses()->count() > 0)
            {
                $cart->setPaymentAddress($user->getAddresses()->first());
                $cart->setShippingAddress($user->getAddresses()->first());
            }
            else
            {
                throw $this->createNotFoundException('Unable to find User Address entity.');
            }
            $form = $this->createForm(new CartUserType($user->getId(), true), $cart);
        }
        $request = $this->getRequest();
        $form->bind($request);
        $form->isValid();
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $form2 = $this->createForm(new CartBaseType($cart->isSameAddress()), $cart);
        }
        else
        {
            $form2 = $this->createForm(new CartUserType($user->getId(), $cart->isSameAddress()), $cart);
        }
        if($cart->isSameAddress())
        {
            $post = $request->get($form2->getName());
            $post['shippingAddress'] = $post['paymentAddress'];
            if(!$user)
            {
                unset($post['shippingAddress']['TIN']);
                unset($post['shippingAddress']['OIN']);
                unset($post['shippingAddress']['VATIN']);
            }
            $request->request->set($form2->getName(), $post);
        }
        $form2->bind($request);
        if($form2->isValid())
        {
            $this->setCart($cart);
            return $this->redirect($this->generateUrl('checkout_order'));
        }
        
        
        return $this->render('SiteShopBundle:Cart:index.html.twig', array(
            'form'   => $form2->createView(),
            'cart' => $cart,
        ));
    }
    
    public function addAction($product, $price, $amount = 1)
    {
        return $this->render('SiteShopBundle:Cart:add.html.twig', array(
            'product' => $product,
            'price' => $price,
            'amount' => $amount
        ));
    }
    
    public function addItemAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST') 
        {
            $cart = $this->getCart();
            $items = $request->get('items', array());
            if(!empty($items))
            {
                foreach($items as $data)//for all posted items for cart
                {
                    if(!empty($data['option']))
                    {
                        foreach($data['option'] as $key => $val)
                        {
                            if(empty($val))
                            {
                                unset($data['option'][$key]); //clear posted empty option values
                            }
                        }
                    }
                    
                    if(!empty($data['variation']))
                    {
                        foreach($data['variation'] as $key => $val)
                        {
                            if(empty($val))
                            {
                                unset($data['variation'][$key]); //clear posted emtpy variation values
                            }
                        }
                    }
                    
                    $index = $cart->findItem($data);
                    $new = false;
                    if($index === false)
                    {
                        $item = new CartItem();
                        $index = $cart->getItemsCount();
                        $new = true;
                    }
                    else
                    {
                        $item = $cart->getItem($index);
                        $new = false;
                    }
                    if($item != null)
                    {
                        $item->addAmount($data['amount']);
                        if($new)
                        {
                            $item->setProductId($data['productId']);
                            if(!empty($data['option']))
                            {
                                foreach($data['option'] as $oid)
                                {
                                    if(!empty($oid))
                                    {
                                        $option = $em->getRepository('CoreProductBundle:ProductOption')->find($oid);
                                        if(!empty($option))
                                        {
                                            $item->addOption($option);
                                        }
                                    }
                                }
                            }
                            if(!empty($data['variation']))
                            {
                                foreach($data['variation'] as $vid)
                                {
                                    /*if(!empty($vid))
                                    {
                                        $variation = $em->getRepository('NwsProductStockBundle:StockVariation')->find($vid);
                                        if(!empty($variation))
                                        {
                                            $item->addVariation($variation);
                                            //curency a VAT recalculate
                                            $data['price']= Operator::calculate($data['price'], $variation->getPrice(), $variation->getOperator());
                                            $data['priceVAT']= Operator::calculate($data['priceVAT'], $variation->getPrice(), $variation->getOperator());
                                        }
                                    }*/
                                }
                            }
                            $item->setName($data['name']);
                            $item->setPrice($data['price']);
                            $item->setPriceVAT($data['priceVAT']);
                            if(!empty($data['options']))
                            {
                                $item->setOptions($data['options']);
                            }
                            if(!empty($data['variations']))
                            {
                                $item->setVariations($data['variations']);
                            }
                        }
                        $cart->addItem($item, $index);
                    }
                }
                $this->setCart($cart);
                $target = $request->get('target', $this->generateUrl('cart'));
                return $this->redirect($target);
            }
        }
        return $this->redirect($this->generateUrl('cart'));
    }
    
    public function removeItemAction($index)
    {
        $cart = $this->getCart();
        $cart->removeItem($index);
        $this->setCart($cart);
        return $this->redirect($this->generateUrl('cart'));
    }
    
    public function updateItemAction()
    {
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') 
        {
            $items = $request->get('items', array());
            if(!empty($items))
            { 
                $cart = $this->getCart();
                foreach($items as $key => $value)
                {
                    $cartitem = $cart->getItem($key);
                    if(!empty($cartitem))
                    {
                        if(!empty($value['amount']))
                        {
                            if($value['amount'] !== 0)
                            {
                                $cartitem->setAmount($value['amount']);
                            }
                        }
                    }
                }
                $this->setCart($cart);
            }
        }
        return $this->redirect($this->generateUrl('cart'));
    }
    
    public function clearAction()
    {
        $cart = $this->getCart();
        $cart->clearItems();
        $this->setCart($cart);
        return $this->redirect($this->generateUrl('cart'));
    }
    
    public function infoAction()
    {
        $cart = $this->getCart();
        return $this->render('SiteShopBundle:Cart:info.html.twig', array(
            'cart' => $cart,
        ));
    }
}
