<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

//    public function findTrickBySlug(string $slug)
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.slug = :slug')
//                ->setParameter('slug', $slug)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
}
