<?php

namespace Core\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Core\ShopBundle\Entity\Order;
use Core\ShopBundle\Entity\OrderFilter;
use Core\ShopBundle\Entity\Process;
use Core\ShopBundle\Entity\ProcessOrder;
use Core\ShopBundle\Form\OrderType;
use Core\ShopBundle\Form\OrderFilterType;
use Core\ShopBundle\Form\ProcessType;

/**
 * BaseOrder controller.
 *
 */
class BaseOrderController extends Controller
{
    public function pdfAction($id, $checkUser = true)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('CoreShopBundle:Order')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Order entity.');
        }
        
        if($checkUser)
        {
            $user = $this->getShopUser();
            if($user === null)
            {
                throw $this->createNotFoundException('Unable to find User entity.');
            }
            
            if($user->getId() != $entity->getUser()->getId())
            {
                throw new AccessDeniedException();
            }
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
