<?php

namespace Core\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ProductBundle\Entity\Product;
use Core\MediaBundle\Entity\Media;
use Core\MediaBundle\Entity\Image;
use Core\MediaBundle\Entity\Video;
use Core\MediaBundle\Form\MediaType;
use Core\MediaBundle\Form\ImageType;
use Core\MediaBundle\Form\VideoType;
use Core\MediaBundle\Form\EditImageType;
use Core\MediaBundle\Form\EditVideoType;

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
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('CoreProductBundle:Product')->findMediaByProduct($product);

        $entity = $em->getRepository('CoreProductBundle:Product')->find($product);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }
        
        return $this->render('CoreProductBundle:Media:index.html.twig', array(
            'entities' => $entities,
            'category' => $category,
            'product' => $entity,
        ));
    }

    /**
     * Displays a form to create a new Content entity.
     *
     */
    public function newAction($product, $type, $category = null)
    {
        switch($type)
        {
            case "video":
                $entity = new Video();
                $form   = $this->createForm(new VideoType(), $entity);
                break;
            case "image":
            default:
                $entity = new Image();
                $form   = $this->createForm(new ImageType(), $entity);
                break;
        }

        return $this->render('CoreProductBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'product' => $product,
            'type' => $type
        ));
    }

    /**
     * Creates a new Content entity.
     *
     */
    public function createAction($product, $type, $category = null)
    {
        switch($type)
        {
            case "video":
                $entity = new Video();
                $form   = $this->createForm(new VideoType(), $entity);
                break;
            case "image":
            default:
                $entity = new Image();
                $form   = $this->createForm(new ImageType(), $entity);
                break;
        }
        $request = $this->getRequest();
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($type == 'image')
            {
                $imageOptions = $this->container->getParameter('images');
                $opts = array();
                foreach($this->container->getParameter('product.images') as $val)
                {
                    $opts[$val] = $imageOptions[$val];
                }
                $entity->setOptions($opts);
            }
            $em->persist($entity);
            $em->flush();
            $productEntity = $em->getRepository('CoreProductBundle:Product')->find($product);
            if($productEntity != null)
            {
                $productMedia = $productEntity->addMedia($entity);
                $em->persist($productMedia);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('product_media', array('category' => $category, 'product' => $product)));
            
        }

        return $this->render('CoreProductBundle:Media:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'product' => $product,
            'type' => $type
        ));
    }

    /**
     * Displays a form to edit an existing Content entity.
     *
     */
    public function editAction($id, $product, $type, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        switch($type)
        {
            case "video":
                $entity = $em->getRepository('CoreMediaBundle:Video')->find($id);
                break;
            case "image":
            default:
                $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);
                break;
        }
       

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }
        
        switch($type)
        {
            case "video":
                $editForm = $this->createForm(new EditVideoType(), $entity);
                break;
            case "image":
            default:
                $editForm = $this->createForm(new EditImageType(), $entity);
                break;
        }

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
    public function updateAction($id, $product, $type, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        switch($type)
        {
            case "video":
                $entity = $em->getRepository('CoreMediaBundle:Video')->find($id);
                break;
            case "image":
            default:
                $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);
                break;
        }
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Media entity.');
        }

        switch($type)
        {
            case "video":
                $editForm = $this->createForm(new EditVideoType(), $entity);
                break;
            case "image":
            default:
                $editForm = $this->createForm(new EditImageType(), $entity);
                break;
        }
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
    public function deleteAction($id, $product, $type, $category = null)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = null;
            switch($type)
            {
                case "video":
                     $entity = $em->getRepository('CoreMediaBundle:Video')->find($id);
                    break;
                case "image":
                default:
                    $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);
                    break;
            }
           

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ' . $type . ' entity.');
            }
            if($type == 'image')
            {
                $imageOptions = $this->container->getParameter('images');
                $entity->setOptions($imageOptions);
            }
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
    
    public function moveUpAction($id, $product, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreProductBundle:Product')->find($product);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }
        $productMedia = $entity->getProductMedia($id);
        if($productMedia)
        {
            $productMedia->setPosition($productMedia->getPosition() - $position);
            $em->persist($productMedia);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('product_media', array('category' => $category, 'product' => $product)));
    }
    
    public function moveDownAction($id, $product, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreProductBundle:Product')->find($product);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }
        $productMedia = $entity->getProductMedia($id);
        if($productMedia)
        {
            $productMedia->setPosition($productMedia->getPosition() + $position);
            $em->persist($productMedia);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('product_media', array('category' => $category, 'product' => $product)));
    }
}
