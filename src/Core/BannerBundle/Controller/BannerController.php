<?php

namespace Core\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\BannerBundle\Entity\Banner;
use Core\BannerBundle\Form\BannerType;
use Core\BannerBundle\Form\EditBannerType;

/**
 * Banner controller.
 *
 */
class BannerController extends Controller
{
    /**
     * Lists all Banner entities.
     *
     */
    public function indexAction($category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreBannerBundle:Banner')->getBanners($category);

        return $this->render('CoreBannerBundle:Banner:index.html.twig', array(
            'entities' => $entities,
            'category' => $category
        ));
    }

    /**
     * Finds and displays a Banner entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreBannerBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreBannerBundle:Banner:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Banner entity.
     *
     */
    public function newAction($category)
    {
        $entity = new Banner();
        $form   = $this->createForm(new BannerType(), $entity);

        return $this->render('CoreBannerBundle:Banner:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category
        ));
    }

    /**
     * Creates a new Banner entity.
     *
     */
    public function createAction($category)
    {
        $entity  = new Banner();
        $request = $this->getRequest();
        $form    = $this->createForm(new BannerType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($category !== null)
            {
                $categoryentity = $em->getRepository('CoreCategoryBundle:Category')->find($category);
                if($categoryentity)
                {
                    $entity->setCategory($categoryentity);
                }
            }
            $media = $entity->getMedia();
            $file = $media->getFile();
            if($file)
            {
                $media->setUsedCount(1);
                $em->persist($media);
            }
            else
            {
                $entity->setMedia(null);
            }
            $em->flush();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('banner', array('category' => $category)));
            
        }

        return $this->render('CoreBannerBundle:Banner:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(), 
            'category' => $category
        ));
    }

    /**
     * Displays a form to edit an existing Banner entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreBannerBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $editForm = $this->createForm(new EditBannerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreBannerBundle:Banner:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Banner entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreBannerBundle:Banner')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }

        $editForm   = $this->createForm(new EditBannerType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('banner_edit', array('id' => $id)));
        }

        return $this->render('CoreBannerBundle:Banner:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Banner entity.
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
            $entity = $em->getRepository('CoreBannerBundle:Banner')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Banner entity.');
            }
            $media = $entity->getMedia();
            if($media)
            {
                $media->setUsedCount($media->getUsedCount() - 1);
                $em->persist($media);
            }
            if($entity->getCategory())
            {
                $category = $entity->getCategory()->getID();
            }
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('banner', array('category' => $category)));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function moveUpAction($id, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreBannerBundle:Banner')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }
        $entity->setPosition($entity->getPosition() - $position);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('banner', array('category' => $category)));
    }
    
    public function moveDownAction($id, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreBannerBundle:Banner')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Banner entity.');
        }
        $entity->setPosition($entity->getPosition() + $position);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('banner', array('category' => $category)));
    }
}
