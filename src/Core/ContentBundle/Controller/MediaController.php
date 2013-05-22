<?php

namespace Core\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ContentBundle\Entity\Content;
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
    public function indexAction($content, $category = null)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities = $em->getRepository('CoreContentBundle:Content')
                ->findMediaByContentQueryBuilder($content)
                ->getQuery()
                ->getResult();
        //$paginator = $this->get('knp_paginator');
        //$page = $this->getRequest()->get('page', 1);
       // $pagination = $paginator->paginate(
        //    $query,
       //     $page/*page number*/,
       //     100/*limit per page*/,
       //     array('distinct' => false)
        //);

        return $this->render('CoreContentBundle:Media:index.html.twig', array(
            'entities' => $entities,
            'category' => $category,
            'content' => $content,
        ));
    }

    /**
     * Displays a form to create a new Content entity.
     *
     */
    public function newAction($content, $category = null)
    {
        $entity = new Image();
        $form   = $this->createForm(new ImageType(), $entity);

        return $this->render('CoreContentBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'content' => $content,
        ));
    }

    /**
     * Creates a new Content entity.
     *
     */
    public function createAction($content, $category = null)
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
            $opts['small'] = $imageOptions['small'];
            $opts['large'] = $imageOptions['large'];
            $entity->setOptions($opts);
            $em->persist($entity);
            $em->flush();
            $contetEntity = $em->getRepository('CoreContentBundle:Content')->find($content);
            if($contetEntity != null)
            {
                $contetEntity->getMedia()->add($entity);
                $em->persist($contetEntity);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('content_media', array('category' => $category, 'content' => $content,)));
            
        }

        return $this->render('CoreContentBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'content' => $content,
        ));
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     */
    public function editAction($id, $content, $category = null)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $editForm = $this->createForm(new EditImageType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreContentBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'category' => $category,
            'content' => $content,
        ));
    }

    /**
     * Edits an existing Content entity.
     *
     */
    public function updateAction($id, $content, $category = null)
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

            return $this->redirect($this->generateUrl('content_media_edit', array('id' => $id, 'category' => $category, 'content' => $content,)));
        }

        return $this->render('CoreContentBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'category' => $category,
            'content' => $content,
        ));
    }

    /**
     * Deletes a Content entity.
     *
     */
    public function deleteAction($id, $content, $category = null)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Content entity.');
            }
            $imageOptions = $this->container->getParameter('images');
            $entity->setOptions($imageOptions);
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('content_media', array('category' => $category, 'content' => $content,)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
