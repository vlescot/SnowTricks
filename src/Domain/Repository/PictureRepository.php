<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
     * @param string $fileName
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeByFilename(string $fileName)
    {
        $picture = $this->createQueryBuilder('p')
            ->where('p.filename = :filename')
            ->setParameter('filename', $fileName)
            ->getQuery()
            ->getOneOrNullResult();

        $this->getEntityManager()->remove($picture);
    }
}
