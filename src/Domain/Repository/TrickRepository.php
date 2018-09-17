<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class TrickRepository
 * @package App\Domain\Repository
 */
class TrickRepository extends ServiceEntityRepository implements TrickRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }


    /**
     * @inheritdoc
     */
    public function findAll(): array
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
     * @inheritdoc
     */
    public function save(TrickInterface $trick): void
    {
        $this->_em->persist($trick);
        $this->_em->flush();
    }

    /**
     * @inheritdoc
     */
    public function remove(TrickInterface $trick): void
    {
        $this->_em->remove($trick);
        $this->_em->flush();
    }
}
