<?php

namespace Core\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\NewsletterBundle\Entity\Newsletter;
use Core\NewsletterBundle\Form\NewsletterType;
use Core\NewsletterBundle\Entity\SendNewsletter;
use Core\NewsletterBundle\Form\SendNewsletterType;
use Core\NewsletterBundle\Form\SendGroupNewsletterType;
use Core\NewsletterBundle\Form\SendEmailNewsletterType;

/**
 * Newsletter controller.
 *
 */
class NewsletterController extends Controller
{
    /**
     * Lists all Newsletter entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('CoreNewsletterBundle:Newsletter')
                ->createQueryBuilder('n')
                ->orderBy('n.createdAt', 'desc')
                ->getQuery();
        $page = $this->getRequest()->get("page", 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            200/*limit per page*/
        );

        return $this->render('CoreNewsletterBundle:Newsletter:index.html.twig', array(
            'entities' => $pagination,
        ));
    }

    /**
     * Finds and displays a Newsletter entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreNewsletterBundle:Newsletter:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Newsletter entity.
     *
     */
    public function newAction()
    {
        $entity = new Newsletter();
        $form   = $this->createForm(new NewsletterType(), $entity);

        return $this->render('CoreNewsletterBundle:Newsletter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Newsletter entity.
     *
     */
    public function createAction()
    {
        $entity  = new Newsletter();
        $request = $this->getRequest();
        $form    = $this->createForm(new NewsletterType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newsletter'));
            
        }

        return $this->render('CoreNewsletterBundle:Newsletter:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Newsletter entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $editForm = $this->createForm(new NewsletterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreNewsletterBundle:Newsletter:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Newsletter entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }

        $editForm   = $this->createForm(new NewsletterType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newsletter_edit', array('id' => $id)));
        }

        return $this->render('CoreNewsletterBundle:Newsletter:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Newsletter entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Newsletter entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('newsletter'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function sendAction($id)
    {
        $entity = new SendNewsletter();
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);
        if (!$newsletter) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }
        $entity->setNewsletter($newsletter);
        
        $form   = $this->createForm(new SendNewsletterType(), $entity);

        return $this->render('CoreNewsletterBundle:Newsletter:send.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }
    
    public function doSendAction($id)
    {
        $entity = new SendNewsletter();
        $request = $this->getRequest();
        $newsletter = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);
        if (!$newsletter) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }
        $entity->setNewsletter($newsletter);
        $form   = $this->createForm(new SendNewsletterType(), $entity);
        $form->bind($request);
        if($form->isValid())
        {
            $emails =  $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->getEnabledEmailsQuery();
            $newsletter = $entity->getNewsletter();
            return $this->sendNewsletter($newsletter, $emails->iterate(), true);
        }
        
        return $this->render('CoreNewsletterBundle:Newsletter:send.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }
    
    public function sendGroupAction($id)
    {
        $entity = new SendNewsletter();
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);
        if (!$newsletter) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }
        $entity->setNewsletter($newsletter);
        
        $form   = $this->createForm(new SendGroupNewsletterType(), $entity);

        return $this->render('CoreNewsletterBundle:Newsletter:sendgroup.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }
    
    public function doSendGroupAction($id)
    {
        $entity = new SendNewsletter();
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);
        if (!$newsletter) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }
        $entity->setNewsletter($newsletter);
        $form   = $this->createForm(new SendGroupNewsletterType(), $entity);
        $form->bind($request);
        if($form->isValid())
        {
            $groups = array();
            $egroups = $entity->getGroups();
            if(!empty($egroups))
            {
                foreach($entity->getGroups() as $group)
                {
                    $groups = $group->getId();
                }
                if(!empty($groups))
                {
                    $emails =  $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->getEnabledEmailsInGroupsQuery($groups, $entity->isNot());
                    $newsletter = $entity->getNewsletter();
                    return $this->sendNewsletter($newsletter, $emails->iterate(), true);
                }
            }
        }
        
        return $this->render('CoreNewsletterBundle:Newsletter:send.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }
    
    public function sendEmailAction($id)
    {
        $entity = new SendNewsletter();
        $em = $this->getDoctrine()->getManager();
        $newsletter = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);
        if (!$newsletter) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }
        $entity->setNewsletter($newsletter);
        $query = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->getEnabledEmailsQuery();
        $page = $this->getRequest()->get("page", 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            200/*limit per page*/
        );
        $form   = $this->createForm(new SendEmailNewsletterType($pagination->getItems()), $entity);

        return $this->render('CoreNewsletterBundle:Newsletter:sendemail.html.twig', array(
            'entity' => $entity,
            'entities' => $pagination,
            'form'   => $form->createView(),
            'page'   => $page,
        ));
    }
    
    public function doSendEmailAction($id)
    {
        $entity = new SendNewsletter();
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $newsletter = $em->getRepository('CoreNewsletterBundle:Newsletter')->find($id);
        if (!$newsletter) {
            throw $this->createNotFoundException('Unable to find Newsletter entity.');
        }
        $entity->setNewsletter($newsletter);
        $query = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->getEnabledEmailsQuery();
        $page = $this->getRequest()->get("page", 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            200/*limit per page*/
        );
        $form   = $this->createForm(new SendEmailNewsletterType($pagination->getItems()), $entity);
        $form->bind($request);
        if($form->isValid())
        {
            $emails =  $entity->getEmails();
            if(!empty($emails) && (count($emails) > 0))
            {
                $newsletter = $entity->getNewsletter();
                return $this->sendNewsletter($newsletter, $emails, false);
            }
        }

        return $this->render('CoreNewsletterBundle:Newsletter:sendemail.html.twig', array(
            'entity' => $entity,
            'entities' => $pagination,
            'form'   => $form->createView(),
            'page'   => $page,
        ));
    }
    
    protected function sendNewsletter($newsletter, $emails, $isQuery=false)
    {
        $count = 0;
        foreach($emails as $row)
        {
            $email = ($isQuery) ? $row[0]: $row;
            if(is_object($email))
            {
                $email = $email->getEmail();
            }
            $emails = $this->container->getParameter('default.emails');
            $message = \Swift_Message::newInstance()
                ->setSubject($newsletter->getTitle())   
                ->setFrom($emails['default'], $this->container->getParameter('site_title'))   //settings
                ->setTo(array($email)) //settings admin
                ->setBody($this->renderView('CoreNewsletterBundle:Email:newsletter.html.twig', array(  
                        'entity' => $newsletter,
                    )), 'text/html')
                ->setContentType("text/html");
            $this->get('mailer')->send($message);
            $count++;
        }
        return $this->render('CoreNewsletterBundle:Newsletter:sendsuccess.html.twig', array(
           'count' => $count,
        ));
    }
}
