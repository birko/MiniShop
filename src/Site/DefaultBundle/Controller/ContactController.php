<?php

namespace Site\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\MarketingBundle\Entity\Message;
use Site\DefaultBundle\Form\ContactType;
use Site\DefaultBundle\Form\ContactMultiType;
use Site\DefaultBundle\Form\ContactClaimType;

class ContactController extends Controller
{

    public function contactAction()
    {
        return $this->render("SiteDefaultBundle:Contact:contact.html.twig", array());
    }
    
    
    public function contactFormAction()
    {
        $verificationCode = (string)$this->container->getParameter('contact.verification_code');
        $form =$this->createForm(new ContactType());
        return $this->render("SiteDefaultBundle:Contact:contactForm.html.twig", array(
            'form' => $form->createView(),
            'verification_code' => $verificationCode,
        ));
    }
    
    public function sendContactFormAction()
    {
        $verificationCode = (string)$this->container->getParameter('contact.verification_code');
        $request = $this->getRequest();
        $form =$this->createForm(new ContactType());
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $data = $form->getData();
                if ($verificationCode !== $data['verification_code'])
                {
                    $t = $this->get('translator')->trans('Verification code does not match. You must enter %code%', array('%code%' => $verificationCode));
                    $form->addError(new \Symfony\Component\Form\FormError($t));
                }
                
                if ($form->isValid())
                {
                // send email
                    $t = $this->get('translator')->trans('Contact %subject%', array('%subject%' => $request->getHost()));
                    $emails = $this->container->getParameter('default.emails');
                    $message = \Swift_Message::newInstance()
                            ->setSubject($t)
                            ->setFrom($emails['default'])
                            ->setTo(array($emails['contact']))
                            ->setBody($this->renderView('SiteDefaultBundle:Email:message.html.twig', array(
                                'data'    => $data,
                                'type'   =>  'contact',
                                )), 'text/html')
                            ->setContentType("text/html");
                    $this->get('mailer')->send($message);
                    
                    $em = $this->getDoctrine()->getManager();
                    $msg = new Message();
                    $msg->setType('contact');
                    $msg->setTitle($t);
                    unset($data['verification_code']);
                    unset($data['_token']);
                    $msg->setMessage($data);
                    $em->persist($msg);
                    $em->flush();
                    return $this->render('SiteDefaultBundle:Contact:send.html.twig');
                }
            }
        }
        return $this->render("SiteDefaultBundle:Contact:contactForm.html.twig", array(
            'form' => $form->createView(),
            'verification_code' => $verificationCode,
        ));
    }
    
     public function contactMultiFormAction()
    {
        $verificationCode = (string)$this->container->getParameter('contact.verification_code');
        $form =$this->createForm(new ContactMultiType());
        return $this->render("SiteDefaultBundle:Contact:contactMultiForm.html.twig", array(
            'form' => $form->createView(),
            'verification_code' => $verificationCode,
        ));
    }
    
    public function sendContactMultiFormAction()
    {
        $verificationCode = (string)$this->container->getParameter('contact.verification_code');
        $request = $this->getRequest();
        $form =$this->createForm(new ContactMultiType());
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $data = $form->getData();
                if ($verificationCode !== $data['verification_code'])
                {
                    $t = $this->get('translator')->trans('Verification code does not match. You must enter %code%', array('%code%' => $verificationCode));
                    $form->addError(new \Symfony\Component\Form\FormError($t));
                }
                
                if ($form->isValid())
                {
                // send email
                    $emails = $this->container->getParameter('default.emails');
                    $send = array($emails['contact']);
                    if($data['copy'])
                    {
                        $send[] =$data['email'];
                    }
                    $t = $this->get('translator')->trans('Contact %type% %subject%', array('%subject%' => $request->getHost(), '%type%' => $data['type']));
                    $message = \Swift_Message::newInstance()
                            ->setSubject($t)
                            ->setFrom($emails['default'])
                            ->setTo($send)
                            ->setBody($this->renderView('SiteDefaultBundle:Email:message.html.twig', array(
                                'data'    => $data,
                                'type'   =>  'contactmulti',
                                )), 'text/html')
                            ->setContentType("text/html");
                    $this->get('mailer')->send($message);
                    
                    $em = $this->getDoctrine()->getManager();
                    $msg = new Message();
                    $msg->setType('contactmulti');
                    $msg->setTitle($t);
                    unset($data['verification_code']);
                    unset($data['_token']);
                    unset($data['copy']);
                    if(!empty($data['orderNumber']))
                    {
                        $order = $em->getRepository('CoreShopBundle:Order')->find($data['orderNumber']);
                        if($order)
                        {
                            $msg->setOrder($order);
                        }
                    }
                    $msg->setMessage($data);
                    $em->persist($msg);
                    $em->flush();
                    return $this->render('SiteDefaultBundle:Contact:send.html.twig');
                }
            }
        }
        return $this->render("SiteDefaultBundle:Contact:contactMultiForm.html.twig", array(
            'form' => $form->createView(),
            'verification_code' => $verificationCode,
        ));
    }
    
    public function contactClaimFormAction()
    {
        $verificationCode = (string)$this->container->getParameter('contact.verification_code');
        $form =$this->createForm(new ContactClaimType());
        return $this->render("SiteDefaultBundle:Contact:contactClaimForm.html.twig", array(
            'form' => $form->createView(),
            'verification_code' => $verificationCode,
        ));
    }
    
    public function sendContactClaimFormAction()
    {
        $verificationCode = (string)$this->container->getParameter('contact.verification_code');
        $request = $this->getRequest();
        $form =$this->createForm(new ContactClaimType());
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $data = $form->getData();
                if ($verificationCode !== $data['verification_code'])
                {
                    $t = $this->get('translator')->trans('Verification code does not match. You must enter %code%', array('%code%' => $verificationCode));
                    $form->addError(new \Symfony\Component\Form\FormError($t));
                }
                
                if ($form->isValid())
                {
                // send email
                    $t = $this->get('translator')->trans('Claim order no.:%order% %subject%', array('%subject%' => $request->getHost(), '%order%' => $data['orderNumber']));
                    $emails = $this->container->getParameter('default.emails');
                    $message = \Swift_Message::newInstance()
                            ->setSubject($t)
                            ->setFrom($emails['default'])
                            ->setTo(array($emails['contact']))
                            ->setBody($this->renderView('SiteDefaultBundle:Email:message.html.twig', array(
                                'data'    => $data,
                                'type'   =>  'claim',
                                )), 'text/html')
                                ->setContentType("text/html");
                    $this->get('mailer')->send($message);
                    
                    $em = $this->getDoctrine()->getManager();
                    $msg = new Message();
                    $msg->setType('claim');
                    $msg->setTitle($t);
                    unset($data['verification_code']);
                    unset($data['_token']);
                    if(!empty($data['orderNumber']))
                    {
                        $order = $em->getRepository('CoreShopBundle:Order')->find($data['orderNumber']);
                        if($order)
                        {
                            $msg->setOrder($order);
                        }
                    }
                    $msg->setMessage($data);
                    $em->persist($msg);
                    $em->flush();
                    return $this->render('SiteDefaultBundle:Contact:send.html.twig');
                }
            }
        }
        return $this->render("SiteDefaultBundle:Contact:contactClaimForm.html.twig", array(
            'form' => $form->createView(),
            'verification_code' => $verificationCode,
        ));
    }
}
