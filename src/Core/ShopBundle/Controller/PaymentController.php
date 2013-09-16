<?php

namespace Core\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ShopBundle\Entity\Payment;
use Core\ShopBundle\Form\PaymentType;

/**
 * Payment controller.
 *
 */
class PaymentController extends Controller
{
    /**
     * Lists all Payment entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreShopBundle:Payment')->findAll();

        return $this->render('CoreShopBundle:Payment:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Payment entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreShopBundle:Payment:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Payment entity.
     *
     */
    public function newAction()
    {
        $entity = new Payment();
        $form   = $this->createForm(new PaymentType(true), $entity);

        return $this->render('CoreShopBundle:Payment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Payment entity.
     *
     */
    public function createAction()
    {
        $entity  = new Payment();
        $request = $this->getRequest();
        $form    = $this->createForm(new PaymentType(true), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->recalculate(false);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('payment'));
            
        }

        return $this->render('CoreShopBundle:Payment:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Payment entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $editForm = $this->createForm(new PaymentType(true), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreShopBundle:Payment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Payment entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:Payment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Payment entity.');
        }

        $editForm   = $this->createForm(new PaymentType(true), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $entity->recalculate(false);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('payment_edit', array('id' => $id)));
        }

        return $this->render('CoreShopBundle:Payment:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Payment entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreShopBundle:Payment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Payment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('payment'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
