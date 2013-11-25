<?php

namespace Core\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\MediaBundle\Entity\Video;

/**
 * Image controller.
 *
 */
class VideoController extends Controller
{    
    public function displayAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CoreMediaBundle:Video')->find($id);
        return $this->displayEntityAction($entity);
    }
    
    public function displayEntityAction(Video $entity)
    {
        $webpath = null;
        if($entity !== null)
        {
            $webpath = $entity->getWebPath();
        }
        return $this->render('CoreMediaBundle:Video:display.html.twig', array(
            'video'      => $entity,
            'source'    =>  $webpath,
        ));
    }
}
