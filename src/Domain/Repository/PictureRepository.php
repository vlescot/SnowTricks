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
     * @param Picture $picture
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Picture $picture)
    {
        $em = $this->getEntityManager();

        $em->remove($picture);
        $em->flush();
    }
}
