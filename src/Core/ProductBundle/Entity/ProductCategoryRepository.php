<?php

namespace Core\ProductBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ProductCategoryRepository extends EntityRepository
{
    public function removeProductCategory($categoryId = null, $productId = null)
    {
        $querybuilder = $this->getEntityManager()->createQueryBuilder()
               ->delete("CoreProductBundle:ProductCategory", "pc");
        if($categoryId !== null)
        {
            $querybuilder =  $querybuilder->andWhere("pc.category = :cid")
            ->setParameter('cid', $categoryId);
        }

        if($productId !== null)
        {
            $querybuilder =  $querybuilder->andWhere("pc.product = :pid")
            ->setParameter('pid', $productId);
        }
       $numUpdated = $querybuilder->getQuery()->execute(); 
       return $numUpdated;
    }
    
    public function updateDefaultCategory($productId, $exludeCategoryId)
    {
        $q = $this->getEntityManager()->createQueryBuilder()
                ->update("CoreProductBundle:ProductCategory", "pc")
                ->set("pc.default", '0')
                ->where("pc.category <> :cid")
                ->andWhere("pc.product = :pid")
                ->setParameter('cid', $exludeCategoryId)
                ->setParameter('pid', $productId)
                ->getQuery();
        $numUpdated = $q->execute(); 
        return $numUpdated;
    }
}