<?php

namespace Core\VendorBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * VendorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class VendorRepository extends EntityRepository
{
    public function setHint(\Doctrine\ORM\Query $query)
    {
        return $query->setHint(\Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER, 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker');
    }
    
    public function getVendorsQueryBuilder()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select("v")
            ->from("CoreVendorBundle:Vendor", "v")
            ->addOrderBy("v.name")
            ->addOrderBy("v.id");
    }
    
    public function getVendorsQuery()
    {
        return $this->setHint($this->getVendorsQueryBuilder()->getQuery());
    }
    
    public function getBySlug($slug)
    {
        $query = $this->getVendorsQueryBuilder()
            ->andWhere('v.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery();
        return $this->setHint($query)->getOneOrNullResult();
    }
}