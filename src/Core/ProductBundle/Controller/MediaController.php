<?php

namespace Core\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ProductBundle\Entity\Product;
use Core\MediaBundle\Entity\Media;
use Core\MediaBundle\Entity\Image;
use Core\MediaBundle\Form\MediaType;
use Core\MediaBundle\Form\ImageType;
use Core\MediaBundle\Form\EditImageType;

/**
 * Content controller.
 *
 */
class MediaController extends Controller
{
    /**
     * Lists all Content Media entities.
     *
     */
    public function indexAction($product, $category = null)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('CoreProductBundle:Product')->findMediaByProduct($product);

        return $this->render('CoreProductBundle:Media:index.html.twig', array(
            'entities' => $entities,
            'category' => $category,
            'product' => $product,
        ));
    }

    /**
     * Displays a form to create a new Content entity.
     *
     */
    public function newAction($product, $category = null)
    {
        $entity = new Image();
        $form   = $this->createForm(new ImageType(), $entity);

        return $this->render('CoreProductBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'product' => $product,
        ));
    }

    /**
     * Creates a new Content entity.
     *
     */
    public function createAction($product, $category = null)
    {
        $entity = new Image();
        $form   = $this->createForm(new ImageType(), $entity);
        $request = $this->getRequest();
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $imageOptions = $this->container->getParameter('images');
            $opts = array();
            $opts['thumb'] = $imageOptions['thumb'];
            $opts['product-small'] = $imageOptions['product-small'];
            $opts['product-large'] = $imageOptions['product-large'];
            $opts['large'] = $imageOptions['large'];
            $entity->setOptions($opts);
            $em->persist($entity);
            $em->flush();
            $productEntity = $em->getRepository('CoreProductBundle:Product')->find($product);
            if($productEntity != null)
            {
                $productEntity->getMedia()->add($entity);
                $em->persist($productEntity);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('product_media', array('category' => $category, 'product' => $product)));
            
        }

        return $this->render('CoreProductBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'product' => $product,
        ));
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     */
    public function editAction($id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $editForm = $this->createForm(new EditImageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'category' => $category,
            'product' => $product,
        ));
    }

    /**
     * Edits an existing Content entity.
     *
     */
    public function updateAction($id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $editForm   = $this->createForm(new EditImageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('product_media_edit', array('id' => $id, 'category' => $category, 'product' => $product)));
        }

        return $this->render('CoreProdctBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'category' => $category,
            'product' => $product,
        ));
    }

    /**
     * Deletes a Content entity.
     *
     */
    public function deleteAction($id, $product, $category = null)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Image entity.');
            }
            $imageOptions = $this->container->getParameter('images');
            $entity->setOptions($imageOptions);
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('product_media', array('category' => $category, 'product' => $product)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
