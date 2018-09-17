<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Picture;
use App\Domain\Repository\Interfaces\PictureRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PictureRepository extends ServiceEntityRepository implements PictureRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    /**
     * @inheritdoc
     */
    public function remove(PictureInterface $picture): void
    {
        $this->_em->remove($picture);
        $this->_em->flush();
    }
}
