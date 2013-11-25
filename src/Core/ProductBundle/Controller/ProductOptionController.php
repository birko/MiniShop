<?php

namespace Core\ProductBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ProductBundle\Entity\ProductOption;
use Core\ProductBundle\Form\ProductOptionType;
use Core\CommonBundle\Controller\TranslateController;

/**
 * ProductOption controller.
 *
 */
class ProductOptionController extends TranslateController
{
    protected function saveTranslation($entity, $culture, $translation) 
    {
        $em = $this->getDoctrine()->getManager();
        $entity->setName($translation->getName());
        $entity->setValue($translation->getValue());    
        $entity->setTranslatableLocale($culture);
        $em->persist($entity); 
        $em->flush();
    }
    /**
     * Lists all ProductOption entities.
     *
     */
    public function indexAction($product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreProductBundle:ProductOption')->getOptionsByProductQuery($product)->getResult();

        return $this->render('CoreProductBundle:ProductOption:index.html.twig', array(
            'entities' => $entities,
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Finds and displays a ProductOption entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:ProductOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductOption entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:ProductOption:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new ProductOption entity.
     *
     */
    public function newAction($product, $category = null)
    {
        $entity = new ProductOption();
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures, new ProductOption());
        $form   = $this->createForm(new ProductOptionType(), $entity, array('cultures' => $cultures));

        return $this->render('CoreProductBundle:ProductOption:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Creates a new ProductOption entity.
     *
     */
    public function createAction($product, $category = null)
    {
        $entity  = new ProductOption();
        $request = $this->getRequest();
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures, new ProductOption());
        $form   = $this->createForm(new ProductOptionType(), $entity, array('cultures' => $cultures));
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
            $this->saveTranslations($entity, $cultures);

            return $this->redirect($this->generateUrl('option', array('product'=> $product, 'category' => $category)));
            
        }

        return $this->render('CoreProductBundle:ProductOption:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Displays a form to edit an existing ProductOption entity.
     *
     */
    public function editAction($id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:ProductOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductOption entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        $editForm   = $this->createForm(new ProductOptionType(), $entity, array('cultures' => $cultures));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:ProductOption:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Edits an existing ProductOption entity.
     *
     */
    public function updateAction($id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:ProductOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductOption entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        $editForm   = $this->createForm(new ProductOptionType(), $entity, array('cultures' => $cultures));
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->saveTranslations($entity, $cultures);

            return $this->redirect($this->generateUrl('option_edit', array('id' => $id, 'product'=> $product, 'category' => $category,)));
        }

        return $this->render('CoreProductBundle:ProductOption:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Deletes a ProductOption entity.
     *
     */
    public function deleteAction($id, $product, $category = null)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreProductBundle:ProductOption')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ProductOption entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('option', array( 'product'=> $product, 'category' => $category,)));
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
        $entity = $em->getRepository('CoreProductBundle:ProductOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductOption entity.');
        }
        $position = ($position < 0) ? 0 : $position;
        $entity->setPosition($entity->getPosition() - $position);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('option', array(
            'product'=> $product,
            'category' => $category,
        )));
    }
    
    public function moveDownAction($id, $product, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreProductBundle:ProductOption')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ProductOption entity.');
        }
        $position = ($position < 0) ? 0 : $position;
        $entity->setPosition($entity->getPosition() + $position);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('option', array(
            'product'=> $product,
            'category' => $category,
        )));
    }
}
