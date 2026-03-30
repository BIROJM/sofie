<?php
/**
 * Created by PhpStorm.
 * User: Eugene
 * Date: 23/09/2015
 * Time: 10:41
 */

namespace Sofie\ExpBundle\Entity;


use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sofie\AdminBundle\Entity\Config;

class Pagination
{
    static public function renderPagination(Query $query, $offset=Config::DEFAULT_OFFSET_PAGINATOR, $page=1)
    {
        if($page < 1){
            throw new \InvalidArgumentException("Page introuvable");
        }
        $query->setFirstResult(($page-1)*$offset)
            ->setMaxResults($offset)
        ;
        return new Paginator($query);
    }
}