<?php

namespace Core\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\MediaBundle\Entity\Image;
use Core\MediaBundle\Form\ImageType;
use Core\MediaBundle\Form\EditImageType;

/**
 * Image controller.
 *
 */
class ImageController extends Controller
{    
    public function displayAction($id, $dir, $link_path = null)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CoreMediaBundle:Image')->find($id);
        if($entity !== null)
        {
            $imageOptions = $this->container->getParameter('images');
            if(!file_exists($entity->getAbsolutePath($dir)))
            {
                $entity->update($dir, $imageOptions[$dir]);
            }
            if(!empty($link_path) && isset($imageOptions[$link_path]) && !file_exists($entity->getAbsolutePath($link_path)))
            {
                $entity->update($link_path, $imageOptions[$link_path]);
            }
        }
        return $this->render('CoreMediaBundle:Image:display.html.twig', array(
            'image'      => $entity,
            'source'    => $entity->getWebPath($dir),
            'link_path' => $link_path,

        ));
    }
}
