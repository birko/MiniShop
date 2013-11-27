<?php

namespace Core\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\MediaBundle\Entity\Media;
use Core\MediaBundle\Form\EditImageType;
use Core\MediaBundle\Form\EditVideoType;
use Core\CommonBundle\Controller\TranslateController;

/**
 * Image controller.
 *
 */
class MediaController extends TranslateController
{    
    protected function saveTranslation($entity, $culture, $translation) 
    {
        $em = $this->getDoctrine()->getManager();
        $entity->setFile(null);
        $entity->setTitle($translation->getTitle());
        $entity->setDescription($translation->getDescription());    
        $entity->setTranslatableLocale($culture);
        $em->persist($entity); 
        $em->flush();
    }
    
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('CoreMediaBundle:Media')
                ->getMediaQueryBuilder()
                ->getQuery();
        $paginator = $this->get('knp_paginator');
        $page = $this->getRequest()->get('page', 1);
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            100/*limit per page*/
        );

        return $this->render('CoreMediaBundle:Media:index.html.twig', array(
            'entities' => $pagination,
        ));
    }
    
    /**
     * Displays a form to edit an existing Media entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreMediaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        switch($entity->getType())
        {
            case "video":
                $editForm   = $this->createForm(new EditVideoType(), $entity, array('cultures' => $cultures));
                break;
            case "image":
            default:
                $editForm   = $this->createForm(new EditImageType(), $entity, array('cultures' => $cultures));
                break;
        }
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreMediaBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'cultures' => $cultures,
        ));
    }

    /**
     * Edits an existing Media entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreMediaBundle:Media')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        switch($entity->getType())
        {
            case "video":
                $editForm   = $this->createForm(new EditVideoType(), $entity, array('cultures' => $cultures));
                break;
            case "image":
            default:
                $editForm   = $this->createForm(new EditImageType(), $entity, array('cultures' => $cultures));
                break;
        }
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->saveTranslations($entity, $cultures);
            return $this->redirect($this->generateUrl('media_edit', array('id' => $entity->getId())));
        }

        return $this->render('CoreMediaBundle:Media:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'cultures' => $cultures,
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
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreMediaBundle:Media')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Content entity.');
            }

            if($entity->getType() == 'image')
            {
                $imageOptions = $this->container->getParameter('images');
                $entity->setOptions($imageOptions);
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('media'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
