<?php

namespace Core\ProductBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ProductBundle\Entity\Attribute;
use Core\ProductBundle\Form\AttributeType;
use Core\CommonBundle\Controller\TranslateController;

/**
 * Attribute controller.
 *
 */
class AttributeController extends TranslateController
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
     * Lists all Attribute entities.
     *
     */
    public function indexAction($product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreProductBundle:Attribute')->getAllAttributesByProductQuery($product)->getResult();

        return $this->render('CoreProductBundle:Attribute:index.html.twig', array(
            'entities' => $entities,
            'product'=> $product, 
            'category' => $category,
        ));
    }

    /**
     * Finds and displays a Attribute entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:Attribute')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attribute entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:Attribute:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Attribute entity.
     *
     */
    public function newAction($product, $category = null)
    {
        $entity = new Attribute();
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures, new Attribute());
        $form   = $this->createForm(new AttributeType(), $entity, array('cultures' => $cultures));


        return $this->render('CoreProductBundle:Attribute:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Creates a new Attribute entity.
     *
     */
    public function createAction(Request $request, $product, $category = null)
    {
        $entity  = new Attribute();
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures, new Attribute());
        $form   = $this->createForm(new AttributeType(), $entity, array('cultures' => $cultures));
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

            return $this->redirect($this->generateUrl('attribute', array('product'=> $product, 'category' => $category)));
        }

        return $this->render('CoreProductBundle:Attribute:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'category' => $category,
            'product' => $product,
            'cultures' => $cultures,
        ));
    }

    /**
     * Displays a form to edit an existing Attribute entity.
     *
     */
    public function editAction($id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:Attribute')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attribute entity.');
        }

        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        $editForm   = $this->createForm(new AttributeType(), $entity, array('cultures' => $cultures));
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreProductBundle:Attribute:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Edits an existing Attribute entity.
     *
     */
    public function updateAction(Request $request, $id, $product, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreProductBundle:Attribute')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attribute entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $cultures = $this->container->getParameter('core.cultures');
        $this->loadTranslations($entity, $cultures);
        $editForm   = $this->createForm(new AttributeType(), $entity, array('cultures' => $cultures));
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            $this->saveTranslations($entity, $cultures);

            return $this->redirect($this->generateUrl('attribute_edit', array('id' => $id, 'product'=> $product, 'category' => $category,)));
        }

        return $this->render('CoreProductBundle:Attribute:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'product'=> $product, 
            'category' => $category,
            'cultures' => $cultures,
        ));
    }

    /**
     * Deletes a Attribute entity.
     *
     */
    public function deleteAction(Request $request, $id, $product, $category = null)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreProductBundle:Attribute')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Attribute entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('attribute', array('product'=> $product, 'category' => $category,)));
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
        $entity = $em->getRepository('CoreProductBundle:Attribute')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find attribute entity.');
        }
        $position = ($position < 0) ? 0 : $position;
        $entity->setPosition($entity->getPosition() - $position);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('attribute', array(
            'product'=> $product,
            'category' => $category,
        )));
    }
    
    public function moveDownAction($id, $product, $position, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreProductBundle:Attribute')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Attribute entity.');
        }
        $position = ($position < 0) ? 0 : $position;
        $entity->setPosition($entity->getPosition() + $position);
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('attribute', array(
            'product'=> $product,
            'category' => $category,
        )));
    }
}
