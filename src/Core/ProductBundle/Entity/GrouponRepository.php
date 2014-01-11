<?php

namespace Core\ProductBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CouponRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GrouponRepository extends EntityRepository
{
    public function getProductGrouponsQueryBuilder($product = null, $active = false)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder()
                ->select("gr, p")
                ->from("CoreProductBundle:Groupon", "gr")
                ->join("gr.product", "p");
        if($product !== null)
        {
            $queryBuilder->andWhere("gr.product = :product")
                ->setParameter("product", $product);
        }
        if($active)
        {
            $queryBuilder->andWhere("gr.active = :active")
                ->setParameter("active", $active);
        }
        return $queryBuilder;
    }
}