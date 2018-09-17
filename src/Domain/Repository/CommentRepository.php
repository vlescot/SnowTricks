<?php

declare(strict_types = 1);

namespace App\Domain\Repository;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Repository\Interfaces\CommentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    /**
     * @inheritdoc
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @inheritdoc
     */
    public function save(CommentInterface $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }
}
