<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

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
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
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
        $this->getEntityManager()->persist($trick);
        $this->getEntityManager()->flush();
    }


    /**
     * @param string $slug
     *
     * @return bool
     */
    public function removeBySlug(string $slug)
    {
        $trick = $this->createQueryBuilder('t')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();

        if (null !== $trick) {
            $this->getEntityManager()->remove($trick);
            return true;
        }

        return false;
    }


    /**
     * @param string $slug
     *
     * @return mixed
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTitleBySlug(string $slug)
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.title')
            ->where('t.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->useResultCache(true)
            ->useQueryCache(true)
            ->getOneOrNullResult();

        return $result['title'];
    }
}
