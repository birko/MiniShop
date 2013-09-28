<?php

namespace Core\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ContentBundle\Entity\Content;
use Core\ContentBundle\Form\ContentType;

/**
 * Content controller.
 *
 */
class ContentController extends Controller
{
    /**
     * Lists all Content entities.
     *
     */
    public function indexAction($category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $querybuilder = $em->getRepository('CoreContentBundle:Content')->findContentByCategoryQueryBuilder($category);
        $query = $querybuilder->orderBy('c.id')->getQuery();
        
        $paginator = $this->get('knp_paginator');
        $page = $this->getRequest()->get('page', 1);
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            100/*limit per page*/
        );

        return $this->render('CoreContentBundle:Content:index.html.twig', array(
            'entities' => $pagination,
            'category' => $category,
        ));
    }

    /**
     * Finds and displays a Content entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreContentBundle:Content:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Content entity.
     *
     */
    public function newAction($category = null)
    {
        $entity = new Content();
        $form   = $this->createForm(new ContentType(), $entity);

        return $this->render('CoreContentBundle:Content:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
        ));
    }

    /**
     * Creates a new Content entity.
     *
     */
    public function createAction($category = null)
    {
        $entity  = new Content();
        $request = $this->getRequest();
        $form    = $this->createForm(new ContentType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($category !== null)
            {
                $categoryEntity = $em->getRepository('CoreCategoryBundle:Category')->find($category);
                if($categoryEntity != null)
                {
                    $entity->setCategory($categoryEntity);
                }
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content', array('category'=> $category)));
            
        }

        return $this->render('CoreContentBundle:Content:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
        ));
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $editForm = $this->createForm(new ContentType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreContentBundle:Content:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Content entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreContentBundle:Content')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }

        $editForm   = $this->createForm(new ContentType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('content_edit', array('id' => $id)));
        }

        return $this->render('CoreContentBundle:Content:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Content entity.
     *
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);
        $category = null;
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreContentBundle:Content')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Content entity.');
            }
            
            $imageOptions = $this->container->getParameter('images');
            foreach($entity->getMedia() as $media)
            {
                $media->setOptions($imageOptions);
                $entity->getMedia()->removeElement($media);
                $em->remove($media);
            }
            
            if($entity->getCategory())
            {
                $category = $entity->getCategory()->getId();
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('content', array('category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
