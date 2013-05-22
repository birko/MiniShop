<?php

namespace Core\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ProductBundle\Entity\Stock;
use Core\ProductBundle\Form\StockType;

/**
 * Stock controller.
 *
 */
class StockController extends Controller
{
    /**
     * Lists all Stock entities.
     *
     */
    public function indexAction($product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreProductBundle:Stock')->findByProduct($product);

        return $this->render('CoreProductBundle:Stock:index.html.twig', array(
            'entities' => $entities,
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Finds and displays a Stock entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:Stock')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stock entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:Stock:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Stock entity.
     *
     */
    public function newAction($product, $category = null)
    {
        $entity = new Stock();
        $form   = $this->createForm(new StockType(), $entity);

        return $this->render('CoreProductBundle:Stock:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Creates a new Stock entity.
     *
     */
    public function createAction(Request $request, $product, $category = null)
    {
        $entity  = new Stock();
        $form = $this->createForm(new StockType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $productEntity = $em->getRepository('CoreProductBundle:Product')->find($product);
            if($productEntity != null)
            {
                $entity->setProduct($productEntity);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stock_edit', array('id' => $entity->getId(), 'product'=> $product, 'category' => $category)));
        }

        return $this->render('CoreProductBundle:Stock:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Stock entity.
     *
     */
    public function editAction($id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:Stock')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stock entity.');
        }

        $editForm = $this->createForm(new StockType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:Stock:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Edits an existing Stock entity.
     *
     */
    public function updateAction(Request $request, $id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:Stock')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stock entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new StockType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stock_edit', array('id' => $id, 'product'=> $product, 'category' => $category)));
        }

        return $this->render('CoreProductBundle:Stock:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Deletes a Stock entity.
     *
     */
    public function deleteAction(Request $request, $id, $product, $category = null)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreProductBundle:Stock')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Stock entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('product',array('category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
