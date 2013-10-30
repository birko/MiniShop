<?php

namespace Site\BannerBundle\Controller;

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
    public function bannerAction($category = null, $type = 'banner')
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('CoreBannerBundle:Banner')->getBanners($category);

        return $this->render('SiteBannerBundle:Banner:banner.html.twig', array(
            'entities' => $entities,
            'type' => $type
        ));
    }
}
