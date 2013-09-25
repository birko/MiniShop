<?php

namespace Core\UserTextBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\UserTextBundle\Entity\UserText;
use Core\UserTextBundle\Form\UserTextType;
use Core\UserTextBundle\Form\EditUserTextType;

/**
 * UserText controller.
 *
 */
class UserTextController extends Controller
{
    /**
     * Lists all UserText entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreUserTextBundle:UserText')->createQueryBuilder("ut")
            ->adOrderBy("ut.name", "asc")
            ->adOrderBy("ut.id", "asc")
            ->getQuery()
            ->getResult();

        return $this->render('CoreUserTextBundle:UserText:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a UserText entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreUserTextBundle:UserText')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserText entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreUserTextBundle:UserText:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new UserText entity.
     *
     */
    public function newAction()
    {
        $entity = new UserText();
        $form   = $this->createForm(new UserTextType(), $entity);

        return $this->render('CoreUserTextBundle:UserText:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new UserText entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new UserText();
        $form = $this->createForm(new UserTextType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usertext'));
        }

        return $this->render('CoreUserTextBundle:UserText:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing UserText entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreUserTextBundle:UserText')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserText entity.');
        }

        $editForm = $this->createForm(new EditUserTextType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('CoreUserTextBundle:UserText:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing UserText entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreUserTextBundle:UserText')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find UserText entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new EditUserTextType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('usertext_edit', array('id' => $id)));
        }

        return $this->render('CoreUserTextBundle:UserText:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a UserText entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('CoreUserTextBundle:UserText')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find UserText entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('usertext'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
    
    public function displayAction($name, $create = false)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreUserTextBundle:UserText')->getUserText($name, $create);

        return $this->render('CoreUserTextBundle:UserText:display.html.twig', array(
            'entity'      => $entity,
        ));
    }
}
