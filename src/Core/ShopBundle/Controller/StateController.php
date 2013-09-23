<?php

namespace Core\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ShopBundle\Entity\State;
use Core\ShopBundle\Form\StateType;

/**
 * State controller.
 *
 */
class StateController extends Controller
{
    /**
     * Lists all State entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreShopBundle:State')->findAll();

        return $this->render('CoreShopBundle:State:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a State entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreShopBundle:State:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new State entity.
     *
     */
    public function newAction()
    {
        $entity = new State();
        $form   = $this->createForm(new StateType(), $entity);

        return $this->render('CoreShopBundle:State:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new State entity.
     *
     */
    public function createAction()
    {
        $entity  = new State();
        $request = $this->getRequest();
        $form    = $this->createForm(new StateType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('state'));
            
        }

        return $this->render('CoreShopBundle:State:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing State entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $editForm = $this->createForm(new StateType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreShopBundle:State:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing State entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $editForm   = $this->createForm(new StateType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('state_edit', array('id' => $id)));
        }

        return $this->render('CoreShopBundle:State:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a State entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreShopBundle:State')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find State entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('state'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
