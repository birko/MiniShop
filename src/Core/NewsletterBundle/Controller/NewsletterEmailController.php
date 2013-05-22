<?php

namespace Core\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\NewsletterBundle\Entity\NewsletterEmail;
use Core\NewsletterBundle\Form\NewsletterEmailType;
use Core\NewsletterBundle\Form\SendGroupNewsletterType;

/**
 * NewsletterEmail controller.
 *
 */
class NewsletterEmailController extends Controller
{
    /**
     * Lists all NewsletterEmail entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $query = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->getEmailsQuery();
        $page = $this->getRequest()->get("page", 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            200/*limit per page*/
        );

        return $this->render('CoreNewsletterBundle:NewsletterEmail:index.html.twig', array(
            'entities' => $pagination
        ));
    }

    /**
     * Finds and displays a NewsletterEmail entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterEmail entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreNewsletterBundle:NewsletterEmail:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new NewsletterEmail entity.
     *
     */
    public function newAction()
    {
        $entity = new NewsletterEmail();
        $form   = $this->createForm(new NewsletterEmailType(), $entity);

        return $this->render('CoreNewsletterBundle:NewsletterEmail:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new NewsletterEmail entity.
     *
     */
    public function createAction()
    {
        $entity  = new NewsletterEmail();
        $request = $this->getRequest();
        $form    = $this->createForm(new NewsletterEmailType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            if(!$em->getRepository('CoreNewsletterBundle:NewsletterEmail')->findOneByEmail($entity->getEmail()))
            {
                $em->persist($entity);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('newsletter_email'));
            
        }

        return $this->render('CoreNewsletterBundle:NewsletterEmail:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing NewsletterEmail entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterEmail entity.');
        }

        $editForm = $this->createForm(new NewsletterEmailType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreNewsletterBundle:NewsletterEmail:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing NewsletterEmail entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find NewsletterEmail entity.');
        }

        $editForm   = $this->createForm(new NewsletterEmailType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('newsletter_email_edit', array('id' => $id)));
        }

        return $this->render('CoreNewsletterBundle:NewsletterEmail:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a NewsletterEmail entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CoreNewsletterBundle:NewsletterEmail')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find NewsletterEmail entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('newsletter_email'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
