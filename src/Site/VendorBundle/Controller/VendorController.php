<?php

namespace Site\VendorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VendorController extends Controller
{
    public function indexAction($slug)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $content  = $em->getRepository("CoreVendorBundle:Vendor")->findOneBySlug($slug);
        $page = $this->getRequest()->get("page", 1);
        return $this->render('SiteVendorBundle:Vendor:index.html.twig', array('entity' => $content, 'page' => $page));
    }
    
    public function listAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entities  = $em->getRepository("CoreVendorBundle:Vendor")->findAll();
        return $this->render('SiteVendorBundle:Vendor:list.html.twig', array('entities' => $entities));
    }
}
