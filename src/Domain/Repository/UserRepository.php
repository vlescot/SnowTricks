<?php

namespace App\Domain\Repository;

use App\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getAllUsers()
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->getResult();
    }
}