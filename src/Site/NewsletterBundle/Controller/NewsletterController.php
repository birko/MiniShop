<?php

namespace Site\NewsletterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Core\NewsletterBundle\Form\BaseNewsletterEmailType;
use Core\NewsletterBundle\Entity\NewsletterEmail;

class NewsletterController extends Controller
{
    public function formAction($target = null)
    {
        $entity = new NewsletterEmail();
        $form   = $this->createForm(new BaseNewsletterEmailType(), $entity);
        return $this->render('SiteNewsletterBundle:Newsletter:form.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'target' => $target,
        ));
    }
    
    public function subscribeAction()
    {
        $entity = new NewsletterEmail();
        $form   = $this->createForm(new BaseNewsletterEmailType(), $entity);    
        $request = $this->getRequest();
        $result = false;
        if($request->getMethod() == "POST")
        {
            $form->bind($request);
            if($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                if(!$em->getRepository('CoreNewsletterBundle:NewsletterEmail')->findOneByEmail($entity->getEmail()))
                {
                    $entity->setEnabled(true);
                    $em->persist($entity);
                    $em->flush();
                    $result = true;
                }
            }
        }
        $target= $request->get('_target', null);
        if($target)
        {
            return $this->redirect($target);
        }
        else
        {
            return $this->render('SiteNewsletterBundle:Newsletter:subscribe.html.twig', array(
                'result' => $result,
            ));
        }
    }
}
