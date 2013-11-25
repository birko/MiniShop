<?php

namespace Core\ContentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ContentBundle\Entity\Content;
use Core\ContentBundle\Form\ContentMediaType;
use Core\MediaBundle\Entity\Media;
use Core\MediaBundle\Entity\Image;
use Core\MediaBundle\Form\MediaType;
use Core\MediaBundle\Form\ImageType;
use Core\MediaBundle\Form\EditImageType;
use Core\CommonBundle\Controller\TranslateController;

/**
 * Content controller.
 *
 */
class MediaController extends TranslateController
{
    protected function saveTranslation($entity, $culture, $translation) 
    {
        $em = $this->getDoctrine()->getManager();
        $entity->setTitle($translation->getTitle());
        $entity->setDescription($translation->getDescription());    
        $entity->setTranslatableLocale($culture);
        $em->persist($entity); 
        $em->flush();
    }
    
    /**
     * Lists all Content Media entities.
     *
     */
    public function indexAction($content, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
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
        $entity = $em->getRepository('CoreContentBundle:Content')->find($content);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        
        return $this->render('CoreContentBundle:Media:index.html.twig', array(
            'entities' => $entities,
            'category' => $category,
            'content' => $entity,
        ));
    }

    /**
     * Displays a form to create a new Content entity.
     *
     */
    public function newAction($content, $category = null)
    {
        $entity = new Image();
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures, new Image());
        $form   = $this->createForm(new ImageType(), $entity, array('cultures' => $cultures));

        return $this->render('CoreContentBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'content' => $content,
            'cultures' => $cultures,
        ));
    }

    /**
     * Creates a new Content entity.
     *
     */
    public function createAction($content, $category = null)
    {
        $entity = new Image();
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures, new Image());
        $form   = $this->createForm(new ImageType(), $entity, array('cultures' => $cultures));
        $request = $this->getRequest();
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $imageOptions = $this->container->getParameter('images');
 
            $opts = array();
            foreach($this->container->getParameter('content.images') as $val)
            {
                $opts[$val] = $imageOptions[$val];
            }
            $entity->setOptions($opts);
            $em->persist($entity);
            $em->flush();
            $contetEntity = $em->getRepository('CoreContentBundle:Content')->find($content);
            if($contetEntity != null)
            {
                $contentMedia = $contetEntity->addMedia($entity);
                $em->persist($contentMedia);
                $em->flush();
            }
            $this->saveTranslations($entity, $cultures);
            return $this->redirect($this->generateUrl('content_media', array('category' => $category, 'content' => $content,)));
            
        }

        return $this->render('CoreContentBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'content' => $content,
            'cultures' => $cultures,
        ));
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     */
    public function editAction($id, $content, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        $editForm = $this->createForm(new EditImageType(), $entity, array('cultures' => $cultures));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreContentBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'category' => $category,
            'content' => $content,
            'cultures' => $cultures,
        ));
    }

    /**
     * Edits an existing Content entity.
     *
     */
    public function updateAction($id, $content, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        $editForm = $this->createForm(new EditImageType(), $entity, array('cultures' => $cultures));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->saveTranslations($entity, $cultures);
            return $this->redirect($this->generateUrl('content_media_edit', array('id' => $id, 'category' => $category, 'content' => $content,)));
        }

        return $this->render('CoreContentBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'category' => $category,
            'content' => $content,
            'cultures' => $cultures,
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
            $em = $this->getDoctrine()->getManager();
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
    
    public function moveUpAction($id, $content, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreContentBundle:Content')->find($content);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        $contentMedia = $entity->getContentMedia($id);
        if($contentMedia)
        {
            $contentMedia->setPosition($contentMedia->getPosition() - $position);
            $em->persist($contentMedia);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('content_media', array('category' => $category, 'content' => $content,)));
    }
    
    public function moveDownAction($id, $content, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreContentBundle:Content')->find($content);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        $contentMedia = $entity->getContentMedia($id);
        if($contentMedia)
        {
            $contentMedia->setPosition($contentMedia->getPosition() + $position);
            $em->persist($contentMedia);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('content_media', array('category' => $category, 'content' => $content,)));
    }
    
    public function positionAction($id, $content, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreContentBundle:Content')->find($content);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        $contentMedia = $entity->getContentMedia($id);
        if(!$contentMedia)
        {
            throw $this->createNotFoundException('Unable to find Content Media entity.');
        }
        $form = $this->createForm(new ContentMediaType(), $contentMedia);
        return $this->render('CoreContentBundle:Media:position.html.twig', array(
            'content' => $content, 
            'category' => $category,
            'id' => $id,
            'form' => $form->createView()
        ));
    }
    
    public function positionUpdateAction($id, $content, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreContentBundle:Content')->find($content);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Content entity.');
        }
        $contentMedia = $entity->getContentMedia($id);
        if(!$contentMedia)
        {
            throw $this->createNotFoundException('Unable to find Content Media entity.');
        }
        $form = $this->createForm(new ContentMediaType(), $contentMedia);
        $request = $this->getRequest();
        $form->bind($request);
        if($form->isValid())
        {
            $em->persist($contentMedia);
            $em->flush();
            return $this->redirect($this->generateUrl('content_media', array('category' => $category, 'content' => $content)));
        }
        return $this->render('CoreContentBundle:Media:position.html.twig', array(
            'content' => $content, 
            'category' => $category,
            'id' => $id,
            'form' => $form->createView()
        ));
    }
}
