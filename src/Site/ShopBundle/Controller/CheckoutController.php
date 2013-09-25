<?php
namespace Site\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Site\ShopBundle\Entity\CartItem;
use Site\ShopBundle\Entity\CouponItem;
use Core\ShopBundle\Entity\Address;
use Core\ShopBundle\Entity\Cart;
use Core\ShopBundle\Entity\Order;
use Core\ShopBundle\Entity\OrderItem;
use Core\UserBundle\Entity\User;
use Core\ShopBundle\Form\AddressType;
use Site\ShopBundle\Form\CartBaseAddressType;
use Site\ShopBundle\Form\CartUserAddressType;
use Site\ShopBundle\Form\CartPaymentShippingType;
use Site\ShopBundle\Form\CartOrderType;
use Site\ShopBundle\Form\CartBaseType;

/**
 * Description of CheckoutSiteController
 *
 * @author Birko
 */
class CheckoutController extends ShopController
{
    public function indexAction()
    {
        $this->testCart();
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->render('SiteShopBundle:Checkout:index.html.twig');
        }
        else
        {
            return $this->redirect($this->generateUrl('checkout_user'));
        }
       
    }

    public function userAction()
    {
        return $this->render('SiteShopBundle:Checkout:user.html.twig');
    }
    
    public function userFormAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $user = $this->getShopUser();
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
        
        $form = $this->createForm(new CartUserAddressType($user->getId(), $cart->isSameAddress()), $cart);
        return $this->render('SiteShopBundle:Checkout:userform.html.twig', array(
            'form'   => $form->createView(),
            'user' => $user,
        ));
    }

    public function userAddressAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $request = $this->getRequest();
        $user = $this->getShopUser();
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
        $form = $this->createForm(new CartUserAddressType($user->getId(), $cart->isSameAddress()), $cart);
        $form->bind($request);
        if($cart->isSameAddress())
        {
            $post = $request->get($form->getName());
            $post['shippingAddress'] = $post['paymentAddress'];
            $request->request->set($form->getName(), $post);
        }
        $form = $this->createForm(new CartUserAddressType($user->getId(), $cart->isSameAddress()), $cart);
        $form->bind($request);
        if ($form->isValid()) 
        {
            $this->setCart($cart);
            return $this->redirect($this->generateUrl('checkout_paymentshipping'));
        }
        return $this->render('SiteShopBundle:Checkout:user.html.twig', array(
            'form'   => $form->createView(),
            'user' => $user,
        ));
    }
    
    public function guestAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $addressRequiredConfiguration = $this->container->getParameter("address.required");
        $form = $this->createForm(new CartBaseAddressType($cart->isSameAddress()), $cart, array('address' => array('required' => $addressRequiredConfiguration)));

        return $this->render('SiteShopBundle:Checkout:guestform.html.twig', array(
            'form'   => $form->createView(),
        ));
    }
    
    public function guestAddressAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $request = $this->getRequest();
        $addressRequiredConfiguration = $this->container->getParameter("address.required");
        $form = $this->createForm(new CartBaseAddressType($cart->isSameAddress()), $cart, array('address' => array('required' => $addressRequiredConfiguration)));
        $form->bind($request);
        $form->isValid();
        if($cart->isSameAddress())
        {
            $post = $request->get($form->getName());
            $post['shippingAddress'] = $post['paymentAddress'];
            unset($post['shippingAddress']['TIN']);
            unset($post['shippingAddress']['OIN']);
            unset($post['shippingAddress']['VATIN']);
            $request->request->set($form->getName(), $post);
        }
        $form2 = $this->createForm(new CartBaseAddressType($cart->isSameAddress()), $cart);
        $form2->bind($request);
        if ($form2->isValid()) 
        {
            $this->setCart($cart);
            return $this->redirect($this->generateUrl('checkout_paymentshipping'));
        }
        return $this->render('SiteShopBundle:Checkout:guest.html.twig', array(
            'form'   => $form2->createView(),
        ));
    }
    
    public function paymentShippingAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $state = $cart->getShippingAddress()->getState();
        $em = $this->getDoctrine()->getManager();
        $payments =  $em->getRepository('CoreShopBundle:Payment')->getPaymentQueryBuilder()
            ->getQuery()->getResult();
        if(!empty($payments))
        {
            $cart->setPayment($payments[0]);
        }
        $shippings =  $em->getRepository('CoreShopBundle:Shipping')->getShippingQueryBuilder($state->getId())
            ->getQuery()->getResult();
        if(!empty($shippings))
        {
            $cart->setShipping($shippings[0]);
        }
        $form = $this->createForm(new CartPaymentShippingType($state->getId()), $cart);
        return $this->render('SiteShopBundle:Checkout:paymentshipping.html.twig', array(
            'form'   => $form->createView(),
            'payment' => $payments,
            'shipping' => $shippings,
        ));
    }
    
    public function savePaymentShippingAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $state = $cart->getShippingAddress()->getState();
        $form = $this->createForm(new CartPaymentShippingType(), $cart);
        $request = $this->getRequest();
        $form->bind($request);
        if ($form->isValid()) 
        {
            $this->setCart($cart);
            return $this->redirect($this->generateUrl('checkout_confirm'));
        }
        return $this->render('SiteShopBundle:Checkout:paymentshipping.html.twig', array(
            'form'   => $form->createView(),
        ));
        
    }
    
    public function confirmAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        $form = $this->createForm(new CartOrderType(), $cart);
        if($cart->isSameAddress())
        {
            $cart->setPaymentAddress($cart->getShippingAddress());
        }
        return $this->render('SiteShopBundle:Checkout:confirm.html.twig', array(
            'cart'   => $cart,
            'form' => $form->createView(),
        ));
    }
    
    public function orderAction()
    {
        $this->testCart();
        $cart = $this->getCart(); 
        if($cart->isSameAddress())
        {
            $cart->setPaymentAddress($cart->getShippingAddress());
        }
        $user = $this->getShopUser();
        $sendEmail = null;
        if(!empty($user))
        {
            $sendEmail = $user->getEmail();
        }
        // check if adress has an email fallback user email
        $pemail = $cart->getShippingAddress()->getEmail();
        if(empty($pemail) && !empty($sendEmail))
        {
            $cart->getShippingAddress()->setEmail($sendEmail);
        }
        if(!$cart->isSameAddress())
        {
            $semail = $cart->getPaymentAddress()->getEmail();
            if(empty($semail) && !empty($sendEmail))
            {
                $cart->getPaymentAddress()->setEmail($sendEmail);
            }
        }
        $em = $this->getDoctrine()->getManager();
        $order = new Order();
        $order->setInvoiceAddress($cart->getPaymentAddress());
        if($cart->isSameAddress())
        {
            $order->setDeliveryAddress($cart->getPaymentAddress());
        }
        else
        {
            $order->setDeliveryAddress($cart->getShippingAddress());
        }
        $order->setPrice($cart->getPrice());
        $order->setPriceVAT($cart->getPriceVAT());
        $order->setComment($cart->getComment());
        if(!empty($user))
        {
           $order->setUser($user);
        }
        elseif($this->container->getParameter('site.shop.register_guest'))
        {
            $newUser = new User();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($newUser);
            $newUser->setEnabled(true);
            $newUser->setLogin($order->getInvoiceAddress()->getEmail());
            $newUser->setEmail($order->getInvoiceAddress()->getEmail());
            $hash = md5($newUser->getLogin());
            $password = substr($hash,1,5);
            $passwordhash = $encoder->encodePassword($password, $newUser->getSalt());
            $newUser->setPassword($passwordhash);
            $priceGroup = $em->getRepository('CoreUserBundle:PriceGroup')->findOneBy(array("default" => true));
            if ($priceGroup) {
                $newUser->setPriceGroup($priceGroup); 
            }
            $em->persist($newUser);
            $em->flush();
            $address = $order->getInvoiceAddress();
            $address->setUser($newUser);
            $em->persist($address);
            $newUser->getAddresses()->add($address);
            if(!$cart->isSameAddress())
            {
                $address = $order->getDeliveryAddress();
                $address->setUser($newUser);
                $em->persist($address);
                $newUser->getAddresses()->add($address);
            }
            $em->persist($newUser);
            $em->flush();
            $t = $this->get('translator')->trans('New registration - %subject%', array(
                '%subject%' => $this->container->getParameter('site_title'),
            ));
            $emails = $this->container->getParameter('default.emails');
            $message = \Swift_Message::newInstance()
                    ->setSubject($t)
                    ->setFrom($emails['default'])
                    ->setTo(array($newUser->getEmail()))
                    ->setBody($this->renderView('SiteUserBundle:Email:new.html.twig', array(
                        'login' => $newUser->getLogin(),
                        'password' => $password,
                    )), 'text/html');
            $this->get('mailer')->send($message);
            $order->setUser($newUser);
        }
        
        //items
        foreach($cart->getItems() as $item)
        {
            $orderItem = new OrderItem();
            $orderItem->setAmount($item->getAmount());
            $orderItem->setPrice($item->getPrice());
            $orderItem->setPriceVAT($item->getPriceVAT());
            $orderItem->setName($item->getName());
            $orderItem->setDescription($item->getDescription());
            if($item->getProductId() != null)
            {
                $productEntity = $em->getRepository('CoreProductBundle:Product')->find($item->getProductId());
                if($productEntity !== null)
                {
                    $orderItem->setProduct($productEntity);
                }
                // stock options
                $options = $item->getOptions();
                if(!empty($options))
                {
                    $options = array();
                    foreach($item->getOptions() as $option)
                    {
                        $options[] = "{$option->getName()}: {$option->getValue()}";
                    }
                    $orderItem->setOptions(implode(', ', $options));
                }
            }
            if($item instanceof CouponItem)
            {
                $couponEntity = $em->getRepository("CoreShopBundle:Coupon")->findOneByCode($item->getCode());
                if($couponEntity)
                {
                    $couponEntity->setUsed(true);
                    $couponEntity->setActive(false);
                    $em->persist($couponEntity);
                }
            }
            $orderItem->setOrder($order);
            $order->addItem($orderItem);
        }
        //shipping
        if($cart->getShipping() != null)
        {
            $orderItem = new OrderItem();
            $orderItem->setAmount(1);
            $orderItem->setPrice($cart->getShipping()->getPrice());
            $orderItem->setPriceVAT($cart->getShipping()->getPriceVAT());
            $orderItem->setName($cart->getShipping()->getName());
            $orderItem->setShipping($cart->getShipping());
            $order->setShipping($cart->getShipping());
            $orderItem->setOrder($order);
            $order->addItem($orderItem);
        }
        //payment
        if($cart->getPayment() != null)
        {
            $orderItem = new OrderItem();
            $orderItem->setAmount(1);
            $orderItem->setPrice($cart->getPayment()->getPrice());
            $orderItem->setPriceVAT($cart->getPayment()->getPrice());
            $orderItem->setName($cart->getPayment()->getName());
            $orderItem->setPayment($cart->getPayment());
            $order->setPayment($cart->getPayment());
            $orderItem->setOrder($order);
            $order->addItem($orderItem);
        }
        $em->persist($order);
        $em->flush();
        // Ordder has  "HasLifecycleCallbacks" to create order_number after insert
        $em->persist($order);
        $em->flush();
        
        $sendEmail= $cart->getPaymentAddress()->getEmail();
        //emails
        // TODO: Send emails
        $emails = $this->container->getParameter('default.emails');
        $t = $this->get('translator')->trans('%date% - New order %subject%', array(
            '%subject%' => $this->container->getParameter('site_title'),
            '%date%' => $order->getCreatedAt()->format('Y.m.d'),
        ));
        $message = \Swift_Message::newInstance()
            ->setSubject($t)   
            ->setFrom($emails['default'])   //settings
            ->setTo(array($emails['order'], $sendEmail)) 
            ->setBody($this->renderView('SiteShopBundle:Email:order.html.twig', array(  
                'order' => $order,
            )),"text/html")
            ->setContentType("text/html");
        $this->get('mailer')->send($message);
        $cart->clearItems();
        $this->setCart($cart);
        // TODO: redirect na platobnu branu ak je nejaka
        
        return $this->render('SiteShopBundle:Checkout:order.html.twig');
    }
}

?>
