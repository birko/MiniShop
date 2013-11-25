<?php

namespace Core\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\MediaBundle\Entity\Media;
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
}
