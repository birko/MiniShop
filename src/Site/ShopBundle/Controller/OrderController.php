<?php

namespace Site\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Neonus\Nws\ShopBundle\Controller\OrderSiteController;


class OrderController extends ShopController
{
    /* Lists all Order entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getShopUser();
        if($user === null)
        {
           throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $query = $em->getRepository('CoreShopBundle:Order')
                ->createQueryBuilder('o')
                ->andWhere('o.user = :uid')
                ->orderBy('o.createdAt', 'desc')
                ->setParameter('uid', $user->getId())
                ->getQuery();
        $page = $this->getRequest()->get("page", 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page/*page number*/,
            200/*limit per page*/
        );

        return $this->render('SiteShopBundle:Order:index.html.twig', array(
            'entities' => $pagination
        ));
    }
    
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        
        $user = $this->getShopUser();
        if($user === null)
        {
           throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        if($user->getId() != $entity->getUser()->getId())
        {
            throw new AccessDeniedException();
        }
        return $this->render('SiteShopBundle:Order:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
    
    public function pdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        
        $user = $this->getShopUser();
        if($user === null)
        {
           throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        if($user->getId() != $entity->getUser()->getId())
        {
            throw new AccessDeniedException();
        }
        
        $view = $this->renderView('SiteShopBundle:Order:pdf.html.twig', array(
            'entity'      => $entity,
        ));
        
        $pdf = $this->container->get("white_october.tcpdf")->create();
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();
        $pdf->writeHTML($view, true, false, true, FALSE, '');
        $pdf->lastPage();
        $pdf->Output("order-".$entity->getId().".pdf", "D");
        return new Response();
    }
}
