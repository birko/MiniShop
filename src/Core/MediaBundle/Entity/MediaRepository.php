<?php
/**
 *
 *
 * @author birko
 */
namespace Core\MediaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr;

class MediaRepository extends EntityRepository
{
    public function getMediaQueryBuilder()
    {
        return $this->createQueryBuilder("m")
            ->addOrderBy("m.id", "asc");
    }
}

?>
