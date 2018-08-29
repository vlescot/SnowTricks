<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class TrickRepository
 * @package App\Domain\Repository
 */
class TrickRepository extends ServiceEntityRepository
{
    /**
     * TrickRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }


    /**
     * @return array|mixed
     */
    public function findAll()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.updatedAt', 'DESC')
            ->setCacheable(true)
            ->getQuery()
            ->useResultCache(true)
            ->useQueryCache(true)
            ->getResult();
    }


    /**
     * @param Trick $trick
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Trick $trick)
    {
        $em = $this->getEntityManager();

        $em->persist($trick);
        $em->flush();
    }

    /**
     * @param Trick $trick
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function remove(Trick $trick)
    {
        $em = $this->getEntityManager();

        $em->remove($trick);
        $em->flush();
    }
}
