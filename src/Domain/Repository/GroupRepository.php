<?php
declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Group;
use App\Domain\Repository\Interfaces\GroupRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class GroupRepository extends ServiceEntityRepository implements GroupRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Group::class);
    }
}
